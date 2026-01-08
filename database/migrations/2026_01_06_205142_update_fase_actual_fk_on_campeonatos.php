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
            // 1️⃣ Borrar la FK existente
            $table->dropForeign('campeonatos_fase_actual_id_foreign');

            // 2️⃣ Hacer el campo nullable (IMPORTANTE)
            $table->unsignedBigInteger('fase_actual_id')->nullable()->change();

            // 3️⃣ Crear la FK con ON DELETE SET NULL
            $table->foreign('fase_actual_id')
                ->references('id')
                ->on('fases_campeonato')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
