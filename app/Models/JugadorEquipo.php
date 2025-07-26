<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorEquipo extends Model
{
    use HasFactory;

    protected $table = 'jugador_equipo';
    protected $fillable = ['jugador_id', 'equipo_id', 'fecha_alta', 'fecha_baja'];

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'jugador_equipo')
            ->using(JugadorEquipo::class)
            ->withPivot('fecha_alta', 'fecha_baja')
            ->withTimestamps(); // si usas timestamps en la tabla (created_at, updated_at)
    }

    public function jugadores()
    {
        return $this->belongsToMany(Jugador::class, 'jugador_equipo')
            ->using(JugadorEquipo::class)
            ->withPivot('fecha_alta', 'fecha_baja')
            ->withTimestamps();
    }
}
