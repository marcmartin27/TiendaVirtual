<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sizes')->insert([
            [
                'size' => 38,
                'product_id' => 1,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'size' => 40,
                'product_id' => 1,
                'stock' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}