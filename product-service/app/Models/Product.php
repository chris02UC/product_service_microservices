<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function shop() {
    return $this->belongsTo(Shop::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
