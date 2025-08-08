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
            $table->unsignedBigInteger('cancha_id')->nullable()->after('hora');
            $table->foreign('cancha_id')->references('id')->on('canchas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuentros', function (Blueprint $table) {
            $table->dropForeign(['cancha_id']);
            $table->dropColumn('cancha_id');
        });
    }
};
