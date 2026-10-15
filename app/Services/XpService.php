<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserXp;
use App\Models\XpLog;
use Illuminate\Support\Facades\DB;

class XpService
{
    // Konstanta XP default
    public const XP_PER_KG_PICKUP    = 20;
    public const XP_MISSION_COMPLETE = 10;
    public const XP_WEEKLY_STREAK    = 50;
    public const XP_ARTICLE_READ     = 5;
    public const XP_CHALLENGE_JOIN   = 15;

    /**
     * Method utama — tambah XP custom.
     * Dipakai oleh semua method lain di bawah.
     */
    public function addXp(
        User $user,
        int $bankId,
        int $amount,
        string $source,
        ?int $sourceId = null,
        ?string $description = null
    ): void {
        if ($amount <= 0) return;

        DB::transaction(function () use ($user, $bankId, $amount, $source, $sourceId, $description) {
            // 1. Update / create user_xps
            $userXp = UserXp::firstOrCreate(
                ['user_id' => $user->user_id, 'bank_id' => $bankId],
                ['xp' => 0, 'level' => 1]
            );

            $userXp->xp += $amount;
            $userXp->level = $this->calculateLevel($userXp->xp);
            $userXp->save();

            // 2. Catat log
            XpLog::create([
                'user_id'     => $user->user_id,
                'bank_id'     => $bankId,
                'source'      => $source,
                'source_id'   => $sourceId,
                'xp'          => $amount,
                'description' => $description,
            ]);
        });
    }

    /**
     * XP dari setoran sampah (20 XP per kg).
     */
    public function addXpFromPickup(User $user, int $bankId, float $weightKg, int $pickupId): int
    {
        $xp = (int) round($weightKg * self::XP_PER_KG_PICKUP);

        $this->addXp(
            $user,
            $bankId,
            $xp,
            'pickup',
            $pickupId,
            "Setor sampah {$weightKg} kg"
        );

        return $xp;
    }

    /**
     * XP dari misi (pakai konstanta = 10 XP).
     * CATATAN: Ini hardcoded. Kalau mau dinamis, pakai addXp() langsung.
     */
    public function addXpFromMission(User $user, int $bankId, int $userMissionId, string $missionTitle): int
    {
        $this->addXp(
            $user,
            $bankId,
            self::XP_MISSION_COMPLETE,
            'mission',
            $userMissionId,
            "Menyelesaikan misi: {$missionTitle}"
        );

        return self::XP_MISSION_COMPLETE;
    }

    /**
     * XP bonus streak (50 XP).
     */
    public function addXpFromStreak(User $user, int $bankId): int
    {
        $this->addXp(
            $user,
            $bankId,
            self::XP_WEEKLY_STREAK,
            'streak',
            null,
            'Bonus konsisten 4 minggu'
        );

        return self::XP_WEEKLY_STREAK;
    }

    /**
     * XP dari baca artikel (5 XP).
     */
    public function addXpFromArticle(User $user, int $bankId, int $articleId, string $title): int
    {
        $this->addXp(
            $user,
            $bankId,
            self::XP_ARTICLE_READ,
            'article',
            $articleId,
            "Membaca artikel: {$title}"
        );

        return self::XP_ARTICLE_READ;
    }

    /**
     * XP dari ikut tantangan (15 XP).
     */
    public function addXpFromChallenge(User $user, int $bankId, int $challengeId, string $title): int
    {
        $this->addXp(
            $user,
            $bankId,
            self::XP_CHALLENGE_JOIN,
            'challenge',
            $challengeId,
            "Mengikuti tantangan: {$title}"
        );

        return self::XP_CHALLENGE_JOIN;
    }

    /**
     * Hitung level berdasarkan total XP.
     */
    public function calculateLevel(int $xp): int
    {
        return match (true) {
            $xp >= 1000 => 5,
            $xp >= 801  => 4,
            $xp >= 501  => 3,
            $xp >= 201  => 2,
            default     => 1,
        };
    }
}