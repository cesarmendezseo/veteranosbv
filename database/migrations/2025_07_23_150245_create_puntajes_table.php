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
        Schema::create('puntajes', function (Blueprint $table) {
            $table->id();
            $table->integer('puntos')->default(0);
            $table->integer('dif_goles')->default(0);
            $table->integer('goles_favor')->default(0);
            $table->integer('fair_play')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntajes');
    }
};
