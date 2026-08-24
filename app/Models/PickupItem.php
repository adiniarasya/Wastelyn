<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'pickup_item_id';

    protected $fillable = [
        'pickup_request_id',
        'category_id',
        'weight',
        'description',
    ];

    public function pickupRequest()
    {
        return $this->belongsTo(PickupRequest::class, 'pickup_request_id', 'pickup_request_id');
    }

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'category_id', 'category_id');
    }
}