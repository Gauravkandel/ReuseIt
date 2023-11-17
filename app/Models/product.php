<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'pname',
        'description',
        'price',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function image()
    {
        return $this->hasMany(product_image::class);
    }
    public function homeAppliance()
    {
        return $this->hasOne(HomeAppliance::class);
    }
    public function electronic()
    {
        return $this->hasOne(electronic::class);
    }
    public function furniture()
    {
        return $this->hasOne(furniture::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
