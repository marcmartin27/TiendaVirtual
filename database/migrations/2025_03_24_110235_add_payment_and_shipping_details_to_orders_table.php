<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Añadir columnas de pago
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            
            // Añadir columnas de información de envío
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('apartment')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Eliminar todas las columnas añadidas
            $table->dropColumn([
                'payment_method', 
                'payment_id',
                'first_name',
                'last_name',
                'email',
                'address',
                'apartment',
                'city',
                'postal_code',
                'province',
                'country',
                'phone'
            ]);
        });
    }
};