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
        Schema::create('fases_campeonato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nombre');
            // Ej: "Fase de Grupos", "Liguilla Superior", "Final"

            $table->enum('tipo', [
                'grupos',
                'todos_contra_todos',
                'eliminacion_simple',
                'eliminacion_doble',
                'liguilla_superior',
                'liguilla_inferior'
            ]);

            $table->unsignedInteger('orden');

            $table->json('reglas_clasificacion')->nullable();

            $table->enum('estado', ['pendiente', 'activa', 'finalizada'])
                ->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fases_campeonato');
    }
};
