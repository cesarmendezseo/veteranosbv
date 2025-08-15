<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GruposPosicionesExport implements WithMultipleSheets
{
    protected $posicionesPorGrupo;
    protected $nombreCampeonato;

    public function __construct($posicionesPorGrupo, $nombreCampeonato)
    {
        $this->posicionesPorGrupo = $posicionesPorGrupo;
        $this->nombreCampeonato = $nombreCampeonato;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->posicionesPorGrupo as $grupo => $posiciones) {
            $sheets[] = new PosicionesExport(
                $posiciones,
                ucwords($this->nombreCampeonato) . ' - ' . ucwords($grupo),
                ucwords($grupo) // 👈 Este será el nombre de la hoja
            );
        }

        return $sheets;
    }
}
