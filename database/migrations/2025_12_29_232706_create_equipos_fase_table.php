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
        Schema::create('equipos_fase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fase_id')
                ->constrained('fases_campeonato')
                ->cascadeOnDelete();

            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->cascadeOnDelete();

            $table->foreignId('fase_origen_id')
                ->nullable()
                ->constrained('fases_campeonato');

            $table->unsignedInteger('posicion_origen')->nullable();

            $table->timestamps();

            $table->unique(['fase_id', 'equipo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos_fase');
    }
};
