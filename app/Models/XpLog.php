<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpLog extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'source',
        'source_id',
        'xp',
        'description',
    ];

    protected $casts = [
        'xp' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function wasteBank(): BelongsTo
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'pickup' => 'Setor Sampah',
            'mission' => 'Misi',
            'streak' => 'Bonus Konsisten',
            'article' => 'Baca Artikel',
            'challenge' => 'Tantangan',
            default => ucfirst($this->source),
        };
    }

    public function getSourceIconAttribute(): string
    {
        return match ($this->source) {
            'pickup' => 'bi-truck',
            'mission' => 'bi-bullseye',
            'streak' => 'bi-fire',
            'article' => 'bi-book',
            'challenge' => 'bi-trophy',
            default => 'bi-star',
        };
    }
}