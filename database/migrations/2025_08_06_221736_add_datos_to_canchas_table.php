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
        Schema::table('canchas', function (Blueprint $table) {
            $table->string('provincia')->nullable();
            $table->string('cod_pos')->nullable();
            $table->string('otros')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            //
        });
    }
};
