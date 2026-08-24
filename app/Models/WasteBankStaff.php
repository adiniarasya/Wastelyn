<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteBankStaff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'user_id',
        'waste_bank_id',
        'position',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function wasteBank()
    {
        return $this->belongsTo(WasteBank::class, 'waste_bank_id', 'waste_bank_id');
    }
}