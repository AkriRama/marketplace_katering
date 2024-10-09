<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        foreach(range(1, 10) as $index) {
            Product::create([
                'code_product' => 'Product'. $index,
                'name' => 'Product ' . $index,
                'description' => 'Description for product ' . $index,
                'price' => rand(1000, 10000), 
            ]);
        }
    }
}
