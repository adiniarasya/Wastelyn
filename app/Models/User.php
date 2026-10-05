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

    public function getXpForBank(int $bankId): ?UserXp
    {
        return $this->userXp()->where('bank_id', $bankId)->first();
    }

    public function getActiveXpAttribute(): ?UserXp
    {
        if (!$this->waste_bank_id)
            return null;
        return $this->getXpForBank($this->waste_bank_id);
    }

    public function addPoints($amount)
    {
        $this->points += $amount;
        $this->save();
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
}