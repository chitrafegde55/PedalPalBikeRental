<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'unit_price',
        'stock_count',
        'bike_compatibility'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'stock_count' => 'integer',
        'bike_compatibility' => 'array'
    ];

    public $timestamps = false;
}