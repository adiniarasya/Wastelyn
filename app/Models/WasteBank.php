<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteBank extends Model
{
    use HasFactory;

    protected $primaryKey = 'bank_id';

    protected $fillable = [
        'mitra_id',
        'name',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'opening_hours',   
        'status',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id', 'user_id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'bank_id', 'bank_id');
    }

    public function pickupRequests()
    {
        return $this->hasMany(PickupRequest::class, 'bank_id', 'bank_id');
    }

}