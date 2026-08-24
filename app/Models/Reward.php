<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $primaryKey = 'reward_id';

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'stock',
        'category',
        'image',
        'is_active',
    ];
}