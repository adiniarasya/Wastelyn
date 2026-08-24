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
        'role',
        'content',
        'context',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id', 'session_id');
    }
}