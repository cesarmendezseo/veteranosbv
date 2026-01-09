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
        Schema::table('encuentros', function (Blueprint $table) {
            // borrar FK vieja
            $table->dropForeign(['fase_id']);

            // crear FK correcta
            $table->foreign('fase_id')
                ->references('id')
                ->on('fases_campeonato')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuentros', function (Blueprint $table) {
            $table->dropForeign(['fase_id']);

            // (opcional) volver a la tabla vieja si existiera
            $table->foreign('fase_id')
                ->references('id')
                ->on('campeonato_fases');
        });
    }
};
