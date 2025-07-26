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
        Schema::create('estadistica_jugador_encuentros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jugador_id');
            $table->unsignedBigInteger('encuentro_id');
            $table->integer('goles')->nullable();
            $table->integer('tarjeta_amarilla')->nullable();
            $table->integer('tarjeta_doble_amarilla')->nullable();
            $table->integer('tarjeta_roja')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('campeonato_id')->nullable(); // Permite valores nulos inicialmente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadistica_jugador_encuentros');
    }
};
