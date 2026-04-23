<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeachCruiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'bike_id',
        'model_name',
        'color',
        'frame_size',
        'daily_rate',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'daily_rate' => 'decimal:2'
    ];

    public $timestamps = false;
}