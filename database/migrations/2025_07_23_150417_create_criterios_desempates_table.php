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
        Schema::create('criterios_desempates', function (Blueprint $table) {
            $table->id();
            $table->integer('orden')->default(0); //indica el orden de los criterios
            $table->string('criterio')->enum('status', ['puntos', 'dif_goles', 'goles_favor', 'fair_play']); //nombre del criterio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterios_desempates');
    }
};
