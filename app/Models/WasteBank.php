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

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id', 'user_id');
    }

    public function wargas()
    {
        return $this->hasMany(User::class, 'waste_bank_id', 'bank_id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'bank_id', 'bank_id');
    }

    public function pickupRequests()
    {
        return $this->hasMany(PickupRequest::class, 'bank_id', 'bank_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHasCoordinates($query)
    {
        return $query->whereNotNull('latitude')
            ->whereNotNull('longitude');
    }

    public function scopeWithActiveMitra($query)
    {
        return $query->whereHas('mitra', function ($q) {
            $q->where('role', 'mitra')->where('status', 'active');
        });
    }

    public function scopeNearby($query, float $lat, float $lng)
    {
        return $query->selectRaw("
            *,
            (6371 * acos(
                LEAST(1, GREATEST(-1,
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                ))
            )) AS jarak_km
        ", [$lat, $lng, $lat])
            ->orderBy('jarak_km');
    }

    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isMitraActive(): bool
    {
        return $this->mitra && $this->mitra->status === 'active';
    }
}