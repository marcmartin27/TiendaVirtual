<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('buyers')->insert([
            [
                'email' => 'buyer1@example.com',
                'phone' => '1234567890',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main St, Anytown, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'buyer2@example.com',
                'phone' => '0987654321',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'address' => '456 Elm St, Othertown, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}