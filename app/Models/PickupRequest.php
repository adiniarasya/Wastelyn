<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'pickup_request_id';

    protected $fillable = [
        'user_id',
        'mitra_id',
        'address',
        'pickup_date',
        'pickup_time',
        'notes',
        'status',
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