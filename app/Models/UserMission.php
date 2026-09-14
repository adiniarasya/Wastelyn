<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMission extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_mission_id';

    protected $fillable = [
        'user_id',
        'mission_id',
        'progress',
        'status',
        'started_at',
        'completed_at',
        'created_at',
        'updated_at',
        'unique_code',
        'pickup_requested_at',
        'picked_up_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class, 'mission_id', 'mission_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'user_mission_id', 'user_mission_id');
    }

    public function progressLogs()
    {
        return $this->hasMany(MissionProgressLog::class, 'user_mission_id', 'user_mission_id');
    }

    public function pickupRequest()
    {
        return $this->hasOne(PickupRequest::class, 'user_mission_id', 'user_mission_id');
    }
}
