<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $primaryKey = 'reward_id';

    protected $fillable = [
        'bank_id',
        'name',
        'description',
        'point_required',
        'type',
        'stock',
        'image',
        'status',
    ];

    protected $casts = [
        'point_required' => 'integer',
        'stock' => 'integer',
    ];

    public function bank()
    {
        return $this->belongsTo(WasteBank::class, 'bank_id', 'bank_id');
    }

    public function getWasteBankIdAttribute()
    {
        return $this->attributes['bank_id'];
    }

    public function setWasteBankIdAttribute($value)
    {
        $this->attributes['bank_id'] = $value;
    }
}