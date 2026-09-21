<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope: notifikasi belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Icon Bootstrap sesuai tipe.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'success' => 'bi-check-circle text-success',
            'warning' => 'bi-exclamation-triangle text-warning',
            'danger' => 'bi-x-circle text-danger',
            default => 'bi-info-circle text-info',
        };
    }
}