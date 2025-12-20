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
        Schema::table('sanciones', function (Blueprint $table) {
            // Añadimos el tipo para saber si contamos partidos o días
            $table->enum('medida', ['partidos', 'tiempo'])->default('partidos')->after('motivo');

            // Para sanciones por tiempo
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanciones', function (Blueprint $table) {
            //
        });
    }
};
