<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'code' => 'CAT001',
                'name' => 'Category 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CAT002',
                'name' => 'Category 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}