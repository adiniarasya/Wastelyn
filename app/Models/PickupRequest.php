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
        'user_mission_id',
        'bank_id',
        'mitra_id',
        'waste_category_id',
        'weight_kg',
        'points_earned',
        'co2_saved',
        'pickup_method',
        'pickup_date',
        'pickup_time',
        'address',
        'notes',
        'status',
        'estimasi_berat',
        'berat_aktual',
        'total_harga',
        'alamat',
        'jadwal_penjemputan',
        'jenis_sampah',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'weight_kg' => 'decimal:2',
        'co2_saved' => 'decimal:2',
    ];

    // Route model binding pakai pickup_request_id, bukan id
    public function getRouteKeyName()
    {
        return 'pickup_request_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function bank()
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    // alias, karena controller pakai 'wasteBank'
    public function wasteBank()
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    // mitra_id nunjuk ke users.user_id (user role mitra)
    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id', 'user_id');
    }

    public function userMission()
    {
        return $this->belongsTo(UserMission::class, 'user_mission_id', 'user_mission_id');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id', 'category_id');
    }

    public function items()
    {
        return $this->hasMany(PickupItem::class, 'pickup_request_id', 'pickup_request_id');
    }
}