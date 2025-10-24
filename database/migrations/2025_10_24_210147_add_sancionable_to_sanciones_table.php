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
        Schema::table('sanciones', function (Blueprint $table) {
            $table->unsignedBigInteger('sancionable_id')->nullable()->after('cumplida');
            $table->string('sancionable_type')->nullable()->after('sancionable_id');

            $table->index(['sancionable_id', 'sancionable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->dropIndex(['sancionable_id', 'sancionable_type']);
            $table->dropColumn(['sancionable_id', 'sancionable_type']);
        });
    }
};
