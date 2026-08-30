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
    ];
}