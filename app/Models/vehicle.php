<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'brand',
        'model',
        'year',
        'mileage',
        'condition',
        'color',
        'used_time',
        'fuel_type',
        'owner',
        'transmission_type',
        'vin',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
