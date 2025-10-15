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
            $table->enum('eliminacion_tipo', ['ida', 'ida_vuelta'])->nullable()->after('formato');
            $table->unsignedInteger('total_equipos')->default(0)->after('cantidad_equipos_grupo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->dropColumn(['eliminacion_tipo', 'total_equipos']);
        });
    }
};
