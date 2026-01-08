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
            // 'regular' será el valor por defecto para no romper lo que ya tienes
            $table->string('fase')->default('regular')->after('campeonato_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuentros', function (Blueprint $table) {
            $table->dropColumn('fase');
        });
    }
};
