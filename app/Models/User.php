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
        'xp',
        'points',
        'level',
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
        ];
    }

    // ============ ROLE CHECK ============
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMitra()
    {
        return $this->role === 'mitra';
    }

    public function isWarga()
    {
        return $this->role === 'warga';
    }

    // ============ XP & LEVEL ============
    public function addXp($amount)
    {
        $this->xp += $amount;
        $this->updateLevel();
        $this->save();
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

    public function updateLevel()
    {
        if ($this->xp >= 1000) {
            $this->level = 5; // Eco Legend
        } elseif ($this->xp >= 801) {
            $this->level = 4; // Green Master
        } elseif ($this->xp >= 501) {
            $this->level = 3; // Green Warrior
        } elseif ($this->xp >= 201) {
            $this->level = 2; // Green Explorer
        } else {
            $this->level = 1; // Green Newbie
        }
        $this->save();
    }

    public function getLevelNameAttribute()
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