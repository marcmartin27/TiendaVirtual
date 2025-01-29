<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'size_id' => 1,
                'quantity' => 2,
                'price' => 99.99,
                'total_price' => 199.98,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'size_id' => 2,
                'quantity' => 1,
                'price' => 149.99,
                'total_price' => 149.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}