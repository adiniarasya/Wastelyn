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
        'category',
        'target_amount',
        'target_unit',
        'duration_days',
        'xp_reward',
        'points_reward',
        'min_level',
        'icon',
        'is_active',
    ];
}