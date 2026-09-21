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

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_VERIFICATION = 'waiting_verification';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_ACCEPTED => 'Diambil Mitra',
            self::STATUS_SCHEDULED => 'Dijadwalkan',
            self::STATUS_IN_PROGRESS => 'Dalam Perjalanan',
            self::STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_ACCEPTED => 'bg-info text-dark',
            self::STATUS_SCHEDULED => 'bg-primary',
            self::STATUS_IN_PROGRESS => 'bg-primary',
            self::STATUS_WAITING_VERIFICATION => 'bg-secondary',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    public function isPickup(): bool
    {
        return $this->pickup_method === 'pickup';
    }

    public function isDropOff(): bool
    {
        return $this->pickup_method === 'dropoff';
    }

    public function calculateXp(): int
    {
        return (int) (20 * ($this->berat_aktual ?? 0));
    }

    public function calculatePoints(): int
    {
        $perKg = $this->wasteCategory->point_per_kg ?? 10;

        return (int) ($perKg * ($this->berat_aktual ?? 0));
    }

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

    // mitra_id menunjuk ke users.user_id (user role mitra)
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
        return $this->belongsTo(
            WasteCategory::class,
            'waste_category_id',
            'category_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            PickupItem::class,
            'pickup_request_id',
            'pickup_request_id'
        );
    }
        /**
     * Ambil harga efektif (override mitra atau default admin).
     */
    public function getHargaEfektifAttribute(): array
    {
        $default = [
            'price_per_kg' => $this->wasteCategory->price_per_kg ?? 0,
            'point_per_kg' => $this->wasteCategory->point_per_kg ?? 10,
            'source' => 'admin',
        ];

        if (!$this->mitra_id || !$this->waste_category_id) {
            return $default;
        }

        $override = \App\Models\MitraWastePrice::where('mitra_id', $this->mitra_id)
            ->where('category_id', $this->waste_category_id)
            ->first();

        if ($override) {
            return [
                'price_per_kg' => $override->price_per_kg,
                'point_per_kg' => $default['point_per_kg'],
                'source' => 'mitra',
            ];
        }

        return $default;
    }
}
