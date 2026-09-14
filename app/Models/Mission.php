<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $primaryKey = 'mission_id';

    protected $fillable = [
        'title',
        'description',
        'target',
        'reward_xp',
        'reward_points',
        'start_date',
        'end_date',
        'status',
        'bank_id',
        'type',
        'unit',
        'ai_prompt',
    ];


    public function bank()
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    public function userMissions()
    {
        return $this->hasMany(UserMission::class, 'mission_id', 'mission_id');
    }
}
