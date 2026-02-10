<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // <--- ESTA es la interfaz correcta
use OwenIt\Auditing\Auditable as AuditableTrait; // El trait para la lógica

class JugadorEquipo extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

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
    protected function nombre(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => strtoupper($value),
        );
    }

    protected function apellido(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => strtoupper($value),
        );
    }
}
