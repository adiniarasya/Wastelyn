<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasFactory;

    protected $primaryKey = 'redemption_id';

    protected $fillable = [
        'user_id',
        'reward_id',
        'status',
        'redeemed_at',
        'processed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id', 'reward_id');
    }
}