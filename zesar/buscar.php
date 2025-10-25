<?php

use App\Models\Jugador;

$buscar = trim($this->buscarJugador);

$this->jugadores = Jugador::with('equipo')
    ->where(function ($query) use ($buscar) {
        $palabras = explode(' ', $buscar);

        foreach ($palabras as $palabra) {
            $palabra = trim($palabra);
            if ($palabra !== '') {
                $query->where(function ($subquery) use ($palabra) {
                    $subquery->where('nombre', 'like', "%{$palabra}%")
                        ->orWhere('apellido', 'like', "%{$palabra}%")
                        ->orWhere('documento', 'like', "%{$palabra}%");
                });
            }
        }
    })
    ->get();
