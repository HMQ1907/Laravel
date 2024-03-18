<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 50 ; $i++) {
            Product::create([
                'product_name' => 'product ' . $i,
                'product_image' => null,
                'product_price' => $i,
                'is_active' => rand(0, 1), 
                'description' => 'Desciption ' . $i,
            ]);
        }
    }
}
