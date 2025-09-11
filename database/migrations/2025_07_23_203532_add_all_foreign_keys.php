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

        Schema::table('jugadors', function (Blueprint $table) {
            $table->unsignedBigInteger('equipo_id')->nullable(); // Clave foránea
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('set null');
        });

        Schema::table('encuentros', function (Blueprint $table) {
            $table->foreign('equipo_local_id')->references('id')->on('equipos');
            $table->foreign('equipo_visitante_id')->references('id')->on('equipos');
            $table->foreignId('campeonato_id')->constrained()->onDelete('cascade');
        });

        Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {
            $table->foreign('campeonato_id')->references('id')->on('campeonatos')->onDelete('cascade');
            $table->foreign('jugador_id')->references('id')->on('jugadors');
            $table->foreign('encuentro_id')->references('id')->on('encuentros');
            $table->foreignId('equipo_id')->after('jugador_id')->constrained('equipos')->cascadeOnDelete();
        });

        Schema::table('grupos', function (Blueprint $table) {
            $table->foreignId('campeonato_id')->constrained()->onDelete('cascade');
        });

        Schema::table('puntajes', function (Blueprint $table) {
            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->onDelete('cascade');
        });
        Schema::table('criterios_desempates', function (Blueprint $table) {
            $table->foreignId('campeonato_id')
                ->constrained('campeonatos')
                ->onDelete('cascade');
        });
        Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {});

        Schema::table('sanciones', function (Blueprint $table) {
            $table->foreignId('jugador_id')->constrained();
            $table->foreignId('campeonato_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::table('jugadors', function (Blueprint $table) {
            $table->dropForeign(['equipo_id']);
            $table->dropColumn('equipo_id');
        });

        Schema::dropIfExists('encuentros');

        Schema::dropIfExists('estadistica_jugador_encuentros');

        Schema::table('grupos', function (Blueprint $table) {
            $table->dropForeign(['campeonato_id']);
        });

        Schema::table('puntajes', function (Blueprint $table) {
            $table->dropForeign(['equipo_id']);
        });

        Schema::table('criterios_desempates', function (Blueprint $table) {
            $table->dropForeign(['campeonato_id']);
        });

        Schema::dropIfExists('sanciones');
    }
};
