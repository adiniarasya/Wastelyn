<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submissions';
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'user_mission_id',
        'photo_path',
        'hash',
        'detected_count',
        'ai_response',
        'note',
    ];

    public function userMission()
    {
        return $this->belongsTo(UserMission::class, 'user_mission_id', 'user_mission_id');
    }
}
