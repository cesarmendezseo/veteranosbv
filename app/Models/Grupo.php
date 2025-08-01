<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre'];
    protected $fillable = ['nombre', 'campeonato_id', 'cantidad_equipos', 'descripcion'];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'campeonato_equipo', 'grupo_id', 'equipo_id')
            ->withPivot('campeonato_id')
            ->withTimestamps();
    }
}
