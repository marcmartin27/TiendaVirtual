<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM de status para incluir más estados
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'Pending',
            'Shipped',
            'Processing',
            'Paid',
            'Delivered',
            'Cancelled',
            'Refunded'
        ) NOT NULL DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'Pending',
            'Shipped'
        ) NOT NULL DEFAULT 'Pending'");
    }
};