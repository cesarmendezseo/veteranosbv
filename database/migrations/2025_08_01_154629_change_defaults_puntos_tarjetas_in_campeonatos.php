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
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->integer('puntos_tarjeta_amarilla')->default(0)->change();
            $table->integer('puntos_doble_amarilla')->default(0)->change();
            $table->integer('puntos_tarjeta_roja')->default(0)->change();
            $table->unsignedTinyInteger('puntos_ganado')->default(0)->change();
            $table->unsignedTinyInteger('puntos_empatado')->default(0)->change();
            $table->unsignedTinyInteger('puntos_perdido')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->integer('puntos_tarjeta_amarilla')->default(0)->change();
            $table->integer('puntos_doble_amarilla')->default(0)->change();
            $table->integer('puntos_tarjeta_roja')->default(0)->change();
            $table->unsignedTinyInteger('puntos_ganado')->default(0)->change();
            $table->unsignedTinyInteger('puntos_empatado')->default(0)->change();
            $table->unsignedTinyInteger('puntos_perdido')->default(0)->change();
        });
    }
};
