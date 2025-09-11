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
        Schema::create('sanciones', function (Blueprint $table) {
            $table->id();
            $table->integer('fecha_sancion');
            $table->string('motivo'); // "doble amarilla", "roja directa", "acumulación de 5 amarillas"
            $table->unsignedTinyInteger('partidos_sancionados')->default(1);
            $table->unsignedTinyInteger('partidos_cumplidos')->default(0);
            $table->text('observacion')->nullable();
            $table->boolean('cumplida')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanciones');
    }
};
