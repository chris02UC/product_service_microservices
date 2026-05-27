<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

// Mengizinkan kolom ini diisi data
    protected $fillable = [
        'name', 'description', 'item_image', 'price', 'weight', 'stock', 'shop_id', 'category_id'
    ];

    // Relasi: 1 Produk dimiliki oleh 1 Toko
    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    // Relasi: 1 Produk memiliki 1 Kategori
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
