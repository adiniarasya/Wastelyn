<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
        'price_per_kg',
        'reward_per_kg',
        'point_per_kg',      
        'co2_saved_per_kg',  
        'icon',
    ];

    public function pickupItems()
    {
        return $this->hasMany(PickupItem::class, 'category_id', 'category_id');
    }
}