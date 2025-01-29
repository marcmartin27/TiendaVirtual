<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'buyer_id' => 1,
                'status' => 'Pending',
                'order_date' => now(),
                'shipping_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'buyer_id' => 2,
                'status' => 'Shipped',
                'order_date' => now(),
                'shipping_date' => now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}