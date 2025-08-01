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
        Schema::create('campeonatos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('formato', ['eliminacion_simple', 'eliminacion_doble', 'grupos', 'todos_contra_todos'])->default('todos_contra_todos');
            $table->unsignedBigInteger('cantidad_equipos_grupo')->nullable();
            $table->unsignedBigInteger('cantidad_grupos')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedTinyInteger('puntos_ganado')->default(3);
            $table->unsignedTinyInteger('puntos_empatado')->default(1);
            $table->unsignedTinyInteger('puntos_perdido')->default(0);
            $table->enum('status', ['fase_de_grupos', 'eliminacion_directa', 'todos_contra_todos'])->default('todos_contra_todos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campeonatos');
    }
};
