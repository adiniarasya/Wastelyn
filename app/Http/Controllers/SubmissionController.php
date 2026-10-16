<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\UserMission;
use App\Models\MissionProgressLog;
use App\Services\GeminiService;
use App\Services\XpService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SubmissionController extends Controller
{
    public function store(Request $request, UserMission $userMission, GeminiService $gemini)
    {
        // pastikan yang upload adalah pemilik misi
        abort_if($userMission->user_id !== auth()->user()->user_id, 403);

        // pastikan masih boleh upload
        abort_if($userMission->status !== 'ongoing', 403, 'Misi sudah tidak bisa diupload.');

        $request->validate([
            'photo' => 'required|image|max:5120',
            'note'  => 'nullable|string|max:200',
        ]);

        // 1. simpan file
        $path = $request->file('photo')->store('submissions', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // ⬇️ KOMPRES FOTO
        try {
            $manager = new ImageManager(new Driver());
            $manager->read($fullPath)
                ->scale(width: 1024)
                ->save($fullPath, quality: 70);
        } catch (\Exception $e) {
            // Kalau kompres gagal, lanjut pakai file asli
        }
        // ⬆️ KOMPRES FOTO

        // 2. cek duplikat (hash)
        $hash = md5_file($fullPath);
        if (Submission::where('hash', $hash)->exists()) {
            @unlink($fullPath);
            return back()->with('error', 'Foto ini sudah pernah dikirim.');
        }

        // 3. AI analisis
        $mission = $userMission->mission;
        $prompt = $mission->ai_prompt
            ?? 'Analisis gambar ini. Jawab JSON: {"valid": bool, "count": int, "reason": string}';
        $ai = $gemini->analyzeImage($fullPath, $prompt);

        $valid = $ai['valid'] ?? false;
        $count = $valid ? (int) ($ai['count'] ?? 0) : 0;

        // 4. simpan submission
        $submission = Submission::create([
            'user_mission_id' => $userMission->user_mission_id,
            'photo_path'      => $path,
            'hash'            => $hash,
            'detected_count'  => $count,
            'ai_response'     => json_encode($ai),
            'note'            => $request->note,
        ]);

        if (!$valid) {
            return back()->with(
                'error',
                'AI tidak bisa validasi: ' . ($ai['reason'] ?? 'coba foto lebih jelas')
            );
        }

        // 5. hitung progress
        $progressBefore = $userMission->progress;
        $progressAfter  = min($progressBefore + $count, $mission->target);
        $increment      = $progressAfter - $progressBefore;

        // 6. catat log
        MissionProgressLog::create([
            'user_mission_id'    => $userMission->user_mission_id,
            'submission_id'      => $submission->submission_id,
            'progress'           => $progressAfter,
            'progress_increment' => $increment,
            'progress_after'     => $progressAfter,
            'description'        => "AI mendeteksi {$count} {$mission->unit}",
        ]);

        // 7. update state
        $userMission->progress = $progressAfter;

        $missionCompleted = false;

        if ($progressAfter >= $mission->target) {
            $userMission->status = 'ready_pickup';
            $userMission->completed_at = now();
            $missionCompleted = true;
        }

        $userMission->save();

        // 8. ⬇️ KASIH REWARD kalau misi selesai
        if ($missionCompleted) {
            $user = auth()->user();

            // XP misi (diambil dari form mitra: $mission->reward_xp)
            if ($mission->reward_xp > 0) {
                app(XpService::class)->addXp(
                    $user,
                    $mission->bank_id,
                    $mission->reward_xp,
                    'mission',
                    $userMission->user_mission_id,
                    "Menyelesaikan misi: {$mission->title}"
                );
            }

            // Poin misi (diambil dari form mitra: $mission->reward_points)
            if ($mission->reward_points > 0) {
                $user->addPoints($mission->reward_points);
            }

            // ⬇️ Update streak mingguan
            $user->updateStreak();
        }

        // 9. redirect dengan pesan yang sesuai
        if ($missionCompleted) {
            return back()->with(
                'success',
                "🎉 Misi selesai! +{$mission->reward_xp} XP & +{$mission->reward_points} Poin. " .
                    "Tunggu mitra jemput sampahmu ya."
            );
        }

        return back()->with(
            'success',
            "Berhasil! +{$increment} {$mission->unit}. " .
                "Progress: {$progressAfter}/{$mission->target}"
        );
    }
}