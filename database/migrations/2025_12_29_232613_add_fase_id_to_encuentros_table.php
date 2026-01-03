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

            if (!Schema::hasColumn('encuentros', 'fase_id')) {
                $table->foreignId('fase_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('fases_campeonato')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuentros', function (Blueprint $table) {
            //
        });
    }
};
