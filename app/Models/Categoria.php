<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'descripcion'];
    protected $fillable = ['nombre', 'descripcion'];

    public function campeonatos()
    {
        return $this->hasMany(Campeonato::class);
    }
}
