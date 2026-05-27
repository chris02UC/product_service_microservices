<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'money', 
        'province', 'province_id', 'city', 'city_id', 
        'district', 'district_id', 'created_by', 'user_id'
    ];

    // Relasi: 1 Toko memiliki banyak Produk
    public function products() {
        return $this->hasMany(Product::class);
    }
}
