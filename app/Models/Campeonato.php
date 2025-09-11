<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'formato'];
    protected $fillable = [
        'nombre',
        'categoria_id',
        'formato',
        'puntos_ganado',
        'puntos_empatado',
        'puntos_perdido',
        'puntos_tarjeta_amarilla',
        'puntos_doble_amarilla',
        'puntos_tarjeta_roja',
        'cantidad_equipos_grupo',
        'cantidad_grupos',
        'status',
    ];
    protected $casts = [
        'puntos_ganado' => 'integer',
        'puntos_empatado' => 'integer',
        'puntos_perdido' => 'integer',
        'puntos_tarjeta_amarilla' => 'integer',
        'puntos_doble_amarilla' => 'integer',
        'puntos_tarjeta_roja' => 'integer',
    ];
    public function criterioDesempate()
    {
        return $this->hasMany(Criterios_desempate::class)->orderBy('orden');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class)->with('equipos');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'campeonato_equipo', 'campeonato_id', 'equipo_id')
            ->withPivot('grupo_id')
            ->withTimestamps();
    }
}
