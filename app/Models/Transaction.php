<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'user_id',
        'mitra_id',
        'category_id',
        'weight',
        'price_per_kg',
        'total_price',
        'xp_earned',
        'points_earned',
        'status',
        'pickup_address',
        'pickup_date',
        'pickup_time',
        'notes',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }
}