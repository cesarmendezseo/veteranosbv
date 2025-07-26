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
        Schema::create('encuentros', function (Blueprint $table) {
            $table->id();
            $table->integer('fecha_encuentro');
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->unsignedBigInteger('equipo_local_id');
            $table->unsignedBigInteger('equipo_visitante_id');
            $table->integer('gol_local')->default(0);
            $table->integer('gol_visitante')->default(0);
            $table->string('estado')->default('programado');
            $table->string('estadio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuentros');
    }
};
