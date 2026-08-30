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
        'point_required',
        'stock',
        'image',
        'status',
    ];
}