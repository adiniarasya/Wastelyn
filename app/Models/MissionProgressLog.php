<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionProgressLog extends Model
{
    use HasFactory;

    protected $table = 'mission_progress_logs';
    protected $primaryKey = 'progress_log_id';

    protected $fillable = [
        'user_mission_id',
        'submission_id',
        'progress',
        'progress_increment',
        'progress_after',
        'description',
        ];

    public function userMission()
    {
        return $this->belongsTo(UserMission::class, 'user_mission_id', 'user_mission_id');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'submission_id');
    }
}
