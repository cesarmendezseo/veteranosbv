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
        Schema::table('encuentros', function (Blueprint $blueprint) {
            // nullable() es vital porque los partidos programados aún no tienen ganador
            // constrained() crea la relación con la tabla equipos
            $blueprint->foreignId('ganador_id')
                ->nullable()
                ->after('equipo_visitante_id')
                ->constrained('equipos')
                ->onDelete('set null');

            // También es útil guardar el ID del perdedor para estadísticas rápidas
            $blueprint->foreignId('perdedor_id')
                ->nullable()
                ->after('ganador_id')
                ->constrained('equipos')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuentros', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['ganador_id']);
            $blueprint->dropColumn('ganador_id');
            $blueprint->dropForeign(['perdedor_id']);
            $blueprint->dropColumn('perdedor_id');
        });
    }
};
