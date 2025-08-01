<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Criterios_desempate extends Model
{
    use HasFactory;

    protected $fillable = ['campeonato_id', 'orden', 'criterio'];


    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }
}
