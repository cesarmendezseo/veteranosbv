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
        Schema::table('equipos_fase', function (Blueprint $table) {
            // Verificamos si la primera columna no existe
            if (!Schema::hasColumn('equipos_fase', 'clasifico_desde_fase_id')) {
                $table->unsignedBigInteger('clasifico_desde_fase_id')->nullable()->after('equipo_id');

                // Es buena práctica definir la foránea dentro del mismo bloque de creación
                $table->foreign('clasifico_desde_fase_id')
                    ->references('id')
                    ->on('fases_campeonato')
                    ->nullOnDelete();
            }

            // Verificamos si la segunda columna no existe
            if (!Schema::hasColumn('equipos_fase', 'posicion_origen')) {
                $table->unsignedInteger('posicion_origen')->nullable()->after('clasifico_desde_fase_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos_fase', function (Blueprint $table) {
            $table->dropForeign(['clasifico_desde_fase_id']);
            $table->dropColumn(['clasifico_desde_fase_id', 'posicion_origen']);
        });
    }
};
