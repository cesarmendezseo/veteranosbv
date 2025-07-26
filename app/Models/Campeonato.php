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
        'cantidad_equipos_grupo',
        'cantidad_grupos',
        'status',
    ];
    public function criterioDesempate()
    {
        return $this->hasMany(criterios_desempate::class)->orderBy('prioridad');
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
        return $this->belongsToMany(Equipo::class, 'campeonato_equipo')
            ->withPivot('grupo_id')
            ->withTimestamps();
    }
}
