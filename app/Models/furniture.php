<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class furniture extends Model
{
    use HasFactory;
    public $table = 'furnitures';
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
