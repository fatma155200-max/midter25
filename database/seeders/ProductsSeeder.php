<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'code' => 'P001',
            'name' => 'منتج تجريبي 1',
            'model' => 'موديل 1',
            'description' => 'وصف المنتج التجريبي الأول',
            'price' => 100.00,
            'stock' => 10
        ]);

        Product::create([
            'code' => 'P002',
            'name' => 'منتج تجريبي 2',
            'model' => 'موديل 2',
            'description' => 'وصف المنتج التجريبي الثاني',
            'price' => 200.00,
            'stock' => 15
        ]);

        Product::create([
            'code' => 'P003',
            'name' => 'منتج تجريبي 3',
            'model' => 'موديل 3',
            'description' => 'وصف المنتج التجريبي الثالث',
            'price' => 300.00,
            'stock' => 20
        ]);
    }
}