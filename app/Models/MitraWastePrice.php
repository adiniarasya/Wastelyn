<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraWastePrice extends Model
{
    use HasFactory;

    protected $primaryKey = 'mitra_price_id';

    protected $fillable = [
        'mitra_id',
        'category_id',
        'price_per_kg',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'category_id', 'category_id');
    }
}