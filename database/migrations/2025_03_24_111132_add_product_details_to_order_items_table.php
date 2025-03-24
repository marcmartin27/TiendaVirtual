<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Añadir columnas necesarias
            if (!Schema::hasColumn('order_items', 'product_id')) {
                $table->unsignedBigInteger('product_id')->after('order_id');
            }
            
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }
            
            if (!Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 8, 2)->after('product_name');
            }
            
            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1)->after('price');
            }
            
            if (!Schema::hasColumn('order_items', 'size')) {
                $table->string('size')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Eliminar las columnas añadidas
            $table->dropColumn([
                'product_id',
                'product_name',
                'price',
                'quantity',
                'size'
            ]);
        });
    }
};