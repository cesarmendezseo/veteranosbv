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
        Schema::table('fases_campeonato', function (Blueprint $table) {
            if (!Schema::hasColumn('fases_campeonato', 'tipo_fase')) {
                $table->string('tipo_fase')->nullable()->after('tipo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fases_campeonato', function (Blueprint $table) {
            $table->dropColumn('tipo_fase');
        });
    }
};
