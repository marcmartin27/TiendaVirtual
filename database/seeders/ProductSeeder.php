<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'code' => 'P001',
                'name' => 'Product 1',
                'description' => 'Description for product 1',
                'category_id' => 1,
                'price' => 99.99,
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'P002',
                'name' => 'Product 2',
                'description' => 'Description for product 2',
                'category_id' => 2,
                'price' => 149.99,
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}