<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'session_id',
        'sender',
        'message',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id', 'session_id');
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId)->orderBy('message_id');
    }

    public function isFromUser(): bool
    {
        return $this->sender === 'user';
    }
}