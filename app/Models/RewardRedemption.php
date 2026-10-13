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
        'processed_at',
        'redeemed_at',
        'voucher_code',
        'pickup_code',
        'pickup_date',
        'pickup_expires_at',
        'picked_up_at',
        'delivery_note',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'processed_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'pickup_date' => 'date',
        'pickup_expires_at' => 'datetime',
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