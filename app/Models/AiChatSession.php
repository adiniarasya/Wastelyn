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
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'session_id', 'session_id');
    }
}