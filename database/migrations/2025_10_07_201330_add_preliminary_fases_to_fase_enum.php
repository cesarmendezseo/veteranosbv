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
        Schema::table('eliminatorias', function (Blueprint $table) {
            $table->enum('fase', [
                'sesentaicuatroavos', // 64 equipos (o más)
                'treintaidosavos',    // 32 equipos
                'dieciseisavos',      // 16 equipos
                'octavos',            // 8 equipos
                'cuartos',            // 4 equipos
                'semifinal',
                '3er y 4to',          // Partido por el tercer puesto
                'final',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eliminatorias', function (Blueprint $table) {
            // Define el valor anterior si necesitas revertir
            $table->enum('fase', ['octavos', 'cuartos', 'semifinal', '3er y 4to', 'final'])->change();
        });
    }
};
