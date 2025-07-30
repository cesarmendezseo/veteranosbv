<?php

namespace App\Models;

use App\NormalizesAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'apellido', 'email', 'direccion', 'ciudad', 'provincia'];

    protected $fillable = [
        'documento',
        'tipo_documento',
        'nombre',
        'apellido',
        'fecha_nac',
        'num_socio',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'provincia',
        'cod_pos',
        'foto',
        'is_active',
        'equipo_id',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'nacimiento' => 'date:d/m/Y',
    ];


    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    //calcular la edad
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nac)->age;
    }
    public function estadisticasEncuentros()
    {
        return $this->hasMany(EstadisticaJugadorEncuentro::class);
    }
    public function Sanciones()
    {
        return $this->hasMany(Sanciones::class);
    }
    //esta relacion es para registrar que fecha se da de alta o baja un jugador
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'jugador_equipo')
            ->withPivot('fecha_alta', 'fecha_baja')
            ->withTimestamps();
    }
}
