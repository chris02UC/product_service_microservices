<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Provinces (Using dummy RajaOngkir IDs)
        $jatim = Province::create(['id' => 11, 'name' => 'Jawa Timur']);
        $jakarta = Province::create(['id' => 6, 'name' => 'DKI Jakarta']);

        // 2. Create Cities
        $surabaya = City::create(['id' => 444, 'name' => 'Surabaya', 'province_id' => $jatim->id]);
        $jakpus = City::create(['id' => 152, 'name' => 'Jakarta Pusat', 'province_id' => $jakarta->id]);

        // 3. Create Categories
        $catElectronics = Category::create(['name' => 'Elektronik']);
        $catFashion = Category::create(['name' => 'Pakaian']);

        // 4. Create Shops
        $shop1 = Shop::create([
            'name' => 'Toko Gadget Sby',
            'description' => 'Toko elektronik termurah se-Jawa Timur',
            'money' => 0,
            'province' => $jatim->name,
            'province_id' => $jatim->id,
            'city' => $surabaya->name,
            'city_id' => $surabaya->id,
            'district' => 'Gubeng',
            'district_id' => 1234, // Dummy district ID
            'user_id' => 1 // Belongs to user #1 in Auth service
        ]);

        $shop2 = Shop::create([
            'name' => 'Hypebeast Jkt',
            'description' => 'Baju distro kekinian',
            'money' => 500000,
            'province' => $jakarta->name,
            'province_id' => $jakarta->id,
            'city' => $jakpus->name,
            'city_id' => $jakpus->id,
            'district' => 'Menteng',
            'district_id' => 5678, 
            'user_id' => 2 
        ]);

        // 5. Create Products
        Product::create([
            'name' => 'Laptop ROG Gaming',
            'description' => 'Laptop mulus, RAM 16GB, RTX 3060.',
            'item_image' => 'https://dummyimage.com/400x400/1e293b/fff&text=Laptop',
            'price' => 15000000,
            'weight' => 2500, // 2.5 kg
            'stock' => 5,
            'shop_id' => $shop1->id,
            'category_id' => $catElectronics->id,
        ]);

        Product::create([
            'name' => 'Mechanical Keyboard RGB',
            'description' => 'Switches blue, suara clicky.',
            'item_image' => 'https://dummyimage.com/400x400/1e293b/fff&text=Keyboard',
            'price' => 850000,
            'weight' => 1000, // 1 kg
            'stock' => 20,
            'shop_id' => $shop1->id,
            'category_id' => $catElectronics->id,
        ]);

        Product::create([
            'name' => 'Jaket Oversized Hitam',
            'description' => 'Bahan tebal, cocok untuk musim hujan.',
            'item_image' => 'https://dummyimage.com/400x400/0f172a/fff&text=Jaket',
            'price' => 250000,
            'weight' => 600, // 600 grams
            'stock' => 50,
            'shop_id' => $shop2->id,
            'category_id' => $catFashion->id,
        ]);
    }
}