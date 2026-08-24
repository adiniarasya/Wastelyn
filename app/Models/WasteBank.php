<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteBank extends Model
{
    use HasFactory;

    protected $primaryKey = 'waste_bank_id';

    protected $fillable = [
        'mitra_id',
        'name',
        'address',
        'phone',
        'latitude',
        'longitude',
        'operational_hours',
        'is_active',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }
}