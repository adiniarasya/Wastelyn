<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $primaryKey = 'mitra_id';

    protected $fillable = [
        'user_id',
        'bank_name',
        'address',
        'phone',
        'latitude',
        'longitude',
        'operational_hours',
        'description',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'mitra_id', 'mitra_id');
    }
}