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
        Schema::create('eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipo_local_id')->constrained('equipos');
            $table->foreignId('equipo_visitante_id')->constrained('equipos');

            // Instancia del torneo
            $table->enum('fase', ['octavos', 'cuartos', 'semifinal', 'final']);

            // Ida o vuelta
            $table->tinyInteger('partido_numero')->default(1); // 1 = ida, 2 = vuelta

            // Goles en el partido
            $table->unsignedInteger('goles_local')->nullable();
            $table->unsignedInteger('goles_visitante')->nullable();

            // Penales (solo si aplica)
            $table->boolean('definido_por_penales')->default(false);
            $table->unsignedInteger('penales_local')->nullable();
            $table->unsignedInteger('penales_visitante')->nullable();

            // Datos del encuentro
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->string('cancha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eliminatorias');
    }
};
