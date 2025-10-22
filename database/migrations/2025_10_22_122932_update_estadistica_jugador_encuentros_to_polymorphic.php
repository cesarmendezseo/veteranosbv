<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {
            // Eliminar la foreign key si existe
            if (Schema::hasColumn('estadistica_jugador_encuentros', 'encuentro_id')) {
                $table->dropForeign(['encuentro_id']);
                $table->renameColumn('encuentro_id', 'old_encuentro_id');
            }

            // Agregar campos polimórficos solo si no existen
            if (!Schema::hasColumn('estadistica_jugador_encuentros', 'estadisticable_id')) {
                $table->unsignedBigInteger('estadisticable_id')->nullable()->after('jugador_id');
            }

            if (!Schema::hasColumn('estadistica_jugador_encuentros', 'estadisticable_type')) {
                $table->string('estadisticable_type')->nullable()->after('estadisticable_id');
            }
        });

        // Migrar datos existentes si la columna temporal existe
        if (Schema::hasColumn('estadistica_jugador_encuentros', 'old_encuentro_id')) {
            DB::table('estadistica_jugador_encuentros')->update([
                'estadisticable_id' => DB::raw('old_encuentro_id'),
                'estadisticable_type' => 'App\\Models\\Encuentro'
            ]);
        }

        // Eliminar la columna temporal si existe
        Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {
            if (Schema::hasColumn('estadistica_jugador_encuentros', 'old_encuentro_id')) {
                $table->dropColumn('old_encuentro_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {
            Schema::table('estadistica_jugador_encuentros', function (Blueprint $table) {
                $table->unsignedBigInteger('encuentro_id')->nullable();
                $table->dropColumn(['estadisticable_id', 'estadisticable_type']);
            });
        });
    }
};
