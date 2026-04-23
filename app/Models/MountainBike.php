<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MountainBike extends Model
{
    use HasFactory;

    protected $fillable = [
        'bike_id',
        'model_name',
        'brand',
        'gear_count',
        'suspension_type',
        'frame_material',
        'terrain',
        'weight_kg',
        'daily_rate',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'daily_rate' => 'decimal:2',
        'weight_kg' => 'decimal:1',
        'gear_count' => 'integer'
    ];

    public $timestamps = false;
}