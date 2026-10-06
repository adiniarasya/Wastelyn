<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'photo',
        'role',
        'status',
        'xp',
        'points',
        'level',
        'waste_bank_id',
        'onboarding_completed',
        'onboarding_completed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarding_completed' => 'boolean',
            'onboarding_completed_at' => 'datetime',
            'xp' => 'integer',
            'points' => 'integer',
            'level' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role & Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMitra(): bool
    {
        return $this->role === 'mitra';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isOnboarded(): bool
    {
        return (bool) $this->onboarding_completed && !is_null($this->waste_bank_id);
    }

    public function needsOnboarding(): bool
    {
        return $this->isWarga() && !$this->isOnboarded();
    }

    public function homeRoute(): string
    {
        if ($this->isWarga()) {
            return 'user.dashboard';
        }

        if ($this->isMitra()) {
            return 'mitra.dashboard';
        }

        if ($this->isAdmin()) {
            return 'admin.dashboard';
        }

        return 'home';
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function wasteBank()
    {
        return $this->belongsTo(WasteBank::class, 'waste_bank_id', 'bank_id');
    }

    public function managedWasteBank()
    {
        return $this->hasOne(WasteBank::class, 'mitra_id', 'user_id');
    }

    public function userXp()
    {
        return $this->hasMany(UserXp::class, 'user_id', 'user_id');
    }

    public function xpLogs()
    {
        return $this->hasMany(XpLog::class, 'user_id', 'user_id');
    }

    public function aiChatSessions()
    {
        return $this->hasMany(AiChatSession::class, 'user_id', 'user_id');
    }

    public function aiChatMessages()
    {
        return $this->hasManyThrough(
            AiChatMessage::class,
            AiChatSession::class,
            'user_id',
            'session_id',
            'user_id',
            'session_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | XP & Level System
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil data XP user untuk bank sampah tertentu.
     * Return null kalau bankId null atau data tidak ada.
     */
    public function getXpForBank(?int $bankId): ?UserXp
    {
        if (is_null($bankId)) {
            return null;
        }

        return $this->userXp()->where('bank_id', $bankId)->first();
    }

    /**
     * Accessor: $user->active_xp
     * Otomatis ambil XP dari waste_bank_id aktif.
     */
    public function getActiveXpAttribute(): ?UserXp
    {
        if (!$this->waste_bank_id) {
            return null;
        }

        return $this->getXpForBank($this->waste_bank_id);
    }

    /**
     * Tambah XP ke user, lalu update level otomatis.
     */
    public function addXp(int $amount)
    {
        $this->xp += $amount;
        $this->save();

        $this->updateLevel();

        return $this->xp;
    }

    /**
     * Update level user berdasarkan total XP.
     * Aturan: setiap 100 XP naik 1 level, maksimal level 5.
     */
    public function updateLevel()
    {
        $totalXp = $this->xp ?? 0;

        // 0-99 = Lv1, 100-199 = Lv2, dst.
        $newLevel = (int) floor($totalXp / 100) + 1;

        // Batasi 1 s/d 5
        $newLevel = max(1, min($newLevel, 5));

        if ($this->level !== $newLevel) {
            $this->level = $newLevel;
            $this->save();
        }

        return $this->level;
    }

    /*
    |--------------------------------------------------------------------------
    | Points System
    |--------------------------------------------------------------------------
    */

    public function addPoints($amount)
    {
        $this->points += $amount;
        $this->save();

        return $this->points;
    }

    public function deductPoints($amount)
    {
        if ($this->points >= $amount) {
            $this->points -= $amount;
            $this->save();
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getLevelNameAttribute(): string
    {
        $levels = [
            1 => 'Green Newbie',
            2 => 'Green Explorer',
            3 => 'Green Warrior',
            4 => 'Green Master',
            5 => 'Eco Legend',
        ];

        return $levels[$this->level] ?? 'Green Newbie';
    }
}