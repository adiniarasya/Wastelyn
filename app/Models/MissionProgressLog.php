<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionProgressLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'progress_log_id';

    protected $fillable = [
        'user_mission_id',
        'progress',
        'description',
        'created_at',
        'updated_at',
    ];

    public function userMission()
    {
        return $this->belongsTo(UserMission::class, 'user_mission_id', 'user_mission_id');
    }
}