<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buyers', function (Blueprint $table) {
            // Añadir columna user_id después de id
            $table->foreignId('user_id')->nullable()->after('id')->constrained();
        });
    }

    public function down()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};