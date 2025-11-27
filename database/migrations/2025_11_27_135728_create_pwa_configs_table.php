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
        Schema::create('pwa_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('APP futbol');
            $table->string('short_name')->default('Futbol');
            $table->string('background_color')->default('#6777ef');
            $table->string('theme_color')->default('#6777ef');
            $table->string('description')->default('App webFull.');
            $table->string('icon')->default('logo.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pwa_configs');
    }
};
