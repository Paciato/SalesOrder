<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            ['product_name' => 'Baja Ringan 1mm', 'price' => 50000],
            ['product_name' => 'Semen Tiga Roda 40kg', 'price' => 65000],
            ['product_name' => 'Cat Avian 1L', 'price' => 45000],
            ['product_name' => 'Paku 5cm 1kg', 'price' => 27000],
        ]);
    }
}
