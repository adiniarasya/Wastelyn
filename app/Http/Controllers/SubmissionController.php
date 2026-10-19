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
        abort_if($userMission->user_id !== auth()->user()->user_id, 403);

        abort_if($userMission->status !== 'ongoing', 403, 'Misi sudah tidak bisa diupload.');

        $request->validate([
            'photo' => 'required|image|max:5120',
            'note'  => 'nullable|string|max:200',
        ]);

        $path = $request->file('photo')->store('submissions', 'public');
        $fullPath = storage_path('app/public/' . $path);

        try {
            $manager = new ImageManager(new Driver());
            $manager->read($fullPath)
                ->scale(width: 1024)
                ->save($fullPath, quality: 70);
        } catch (\Exception $e) {
        }

        $hash = md5_file($fullPath);
        if (Submission::where('hash', $hash)->exists()) {
            @unlink($fullPath);
            return back()->with('error', 'Foto ini sudah pernah dikirim.');
        }

        $mission = $userMission->mission;
        $prompt = $mission->ai_prompt
            ?? 'Analisis gambar ini. Jawab JSON: {"valid": bool, "count": int, "reason": string}';
        $ai = $gemini->analyzeImage($fullPath, $prompt);

        $valid = $ai['valid'] ?? false;
        $count = $valid ? (int) ($ai['count'] ?? 0) : 0;

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

        $progressBefore = $userMission->progress;
        $progressAfter  = min($progressBefore + $count, $mission->target);
        $increment      = $progressAfter - $progressBefore;

        MissionProgressLog::create([
            'user_mission_id'    => $userMission->user_mission_id,
            'submission_id'      => $submission->submission_id,
            'progress'           => $progressAfter,
            'progress_increment' => $increment,
            'progress_after'     => $progressAfter,
            'description'        => "AI mendeteksi {$count} {$mission->unit}",
        ]);

        $userMission->progress = $progressAfter;

        $missionCompleted = false;

        if ($progressAfter >= $mission->target) {
            $userMission->status = 'ready_pickup';
            $userMission->completed_at = now();
            $missionCompleted = true;
        }

        $userMission->save();

        if ($missionCompleted) {
            $user = auth()->user();

            if ($mission->reward_xp > 0) {