<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    use HasFactory;

    protected $primaryKey = 'session_id';

    protected $fillable = [
        'user_id',
        'title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'session_id', 'session_id')
            ->orderBy('message_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(AiChatMessage::class, 'session_id', 'session_id')
            ->latestOfMany('message_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}