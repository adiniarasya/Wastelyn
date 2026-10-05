<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserXp extends Model
{
    protected $table = 'user_xp';

    protected $fillable = [
        'user_id',
        'bank_id',
        'xp',
        'level',
    ];

    protected $casts = [
        'xp' => 'integer',
        'level' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function wasteBank(): BelongsTo
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    public function getLevelNameAttribute(): string
    {
        return match ($this->level) {
            5 => 'Eco Legend',
            4 => 'Green Master',
            3 => 'Green Warrior',
            2 => 'Green Explorer',
            default => 'Green Newbie',
        };
    }

    public function getNextLevelXpAttribute(): ?int
    {
        return match ($this->level) {
            1 => 201,
            2 => 501,
            3 => 801,
            4 => 1000,
            5 => null,
        };
    }

    public function getCurrentLevelMinXpAttribute(): int
    {
        return match ($this->level) {
            1 => 0,
            2 => 201,
            3 => 501,
            4 => 801,
            5 => 1000,
            default => 0,
        };
    }

    public function getProgressPercentAttribute(): int
    {
        $next = $this->next_level_xp;
        if ($next === null)
            return 100;

        $min = $this->current_level_min_xp;
        $range = $next - $min;
        if ($range <= 0)
            return 0;

        $current = $this->xp - $min;
        return (int) min(100, max(0, round(($current / $range) * 100)));
    }
}