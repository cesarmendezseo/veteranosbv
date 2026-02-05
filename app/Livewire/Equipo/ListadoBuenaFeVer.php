<?php

namespace App\Livewire\Equipo;

use App\Exports\CampeonatoCompletoExport;
use App\Exports\ListadoBuenaFeExport;
use App\Models\Campeonato;
use App\Models\CampeonatoJugadorEquipo;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Sanciones;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ListadoBuenaFeVer extends Component
{
    public $campeonato;
    public $campeonatoId;
    public $equiposDelCampeonato;
    public $equipoSeleccionado;
    public $jugadoresEquipos = [];
    public $fecha;
    public $itemId;
    public $sanciones;

    // 🆕 NUEVAS PROPIEDADES PARA ENCUENTROS
    public $encuentrosDisponibles = [];
    public $encuentroSeleccionado;
    public $fechaJornada;
    public $nombreCancha;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->campeonato = Campeonato::with('equipos')->find($campeonatoId);

        $this->equiposDelCampeonato = $this->campeonato
            ? $this->campeonato->equipos->sortBy(fn($equipo) => strtoupper(trim($equipo->nombre)))
            : collect();
    }

    public function calcularPeriodoSancion($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin || $fechaInicio === '' || $fechaFin === '') {
            return null;
        }

        try {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $fin    = \Carbon\Carbon::parse($fechaFin);

            $diff = $inicio->diff($fin);

            $resultado = [];

            if ($diff->y > 0) {
                $resultado[] = $diff->y . ($diff->y === 1 ? ' año' : ' años');
            }

            if ($diff->m > 0) {
                $resultado[] = $diff->m . ($diff->m === 1 ? ' mes' : ' meses');
            }

            return $resultado
                ? implode(' y ', $resultado)
                : 'Menos de 1 mes';
        } catch (\Exception $e) {
            return null;
        }
    }

    // 🆕 Cuando se elige un equipo, cargar sus encuentros
    public function updatedEquipoSeleccionado($equipoId)
    {
        if ($this->campeonatoId && $equipoId) {
            // Cargar jugadores
            $this->jugadoresEquipos = CampeonatoJugadorEquipo::with([
                'jugador',
                'jugador.sanciones' => function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
                            ->orWhere(function ($q3) {
                                $q3->whereNotNull('fecha_fin')
                                    ->where('fecha_fin', '>=', now());
                            });
                    });
                }
            ])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('equipo_id', $equipoId)
                ->whereNull('fecha_baja')
                ->get()
                ->map(function ($registro) {
                    $sancionesConPeriodo = $registro->jugador->sanciones->map(function ($sancion) {
                        $sancion->periodo_texto = $this->calcularPeriodoSancion(
                            $sancion->fecha_inicio,
                            $sancion->fecha_fin
                        );
                        return $sancion;
                    });

                    return [
                        'jugador' => $registro->jugador,
                        'sanciones' => $sancionesConPeriodo,
                    ];
                })
                ->unique(fn($item) => $item['jugador']->id)
                ->sortBy(fn($item) => strtolower($item['jugador']->apellido))
                ->values();

            // 🆕 Cargar encuentros del equipo seleccionado
            $this->cargarEncuentrosDelEquipo($equipoId);
        }
    }

    // 🆕 Método para cargar encuentros
    public function cargarEncuentrosDelEquipo($equipoId)
    {
        $this->encuentrosDisponibles = Encuentro::where('campeonato_id', $this->campeonatoId)
            ->where(function ($q) use ($equipoId) {
                $q->where('equipo_local_id', $equipoId)
                    ->orWhere('equipo_visitante_id', $equipoId);
            })
            ->with(['equipoLocal', 'equipoVisitante'])
            ->orderBy('fecha_encuentro', 'asc') // Ordenar ascendente para numerar correctamente
            ->get()
            ->map(function ($encuentro, $index) use ($equipoId) {
                $equipoRival = $encuentro->equipo_local_id == $equipoId
                    ? $encuentro->equipoVisitante
                    : $encuentro->equipoLocal;

                $condicion = $encuentro->equipo_local_id == $equipoId ? 'LOCAL' : 'VISITANTE';

                // Número de fecha basado en el índice (1, 2, 3, etc.)
                $numeroFecha = $index + 1;

                // Buscar el campo de cancha
                $cancha = $encuentro->cancha
                    ?? $encuentro->nombre_cancha
                    ?? $encuentro->lugar
                    ?? '';

                return [
                    'id' => $encuentro->id,
                    'label' => 'Fecha ' . $numeroFecha . ' - ' .
                        \Carbon\Carbon::parse($encuentro->fecha_encuentro)->format('d/m/Y') .
                        ' vs ' . strtoupper($equipoRival->nombre) .
                        ' (' . $condicion . ')' .
                        (isset($encuentro->estado) && $encuentro->estado ? ' - ' . $encuentro->estado : ''),
                    'fecha' => $encuentro->fecha_encuentro,
                    'jornada' => $numeroFecha,
                    'cancha' => $cancha,
                    'condicion' => $condicion,
                    'estado' => $encuentro->estado ?? 'Pendiente'
                ];
            })
            ->values(); // Reindexar la colección

        // Resetear selección
        $this->encuentroSeleccionado = null;
        $this->fechaJornada = null;
        $this->nombreCancha = null;
    }

    // 🆕 Cuando se selecciona un encuentro
    public function updatedEncuentroSeleccionado($encuentroId)
    {
        if ($encuentroId) {
            $encuentro = collect($this->encuentrosDisponibles)->firstWhere('id', $encuentroId);

            if ($encuentro) {
                $this->fechaJornada = 'Jornada ' . $encuentro['jornada'];
                $this->fecha = \Carbon\Carbon::parse($encuentro['fecha'])->format('d/m/Y');
                $this->nombreCancha = $encuentro['cancha'];
            }
        }
    }

    // Exportar a Excel
    public function exportarJugadores()
    {
        $equipo = Equipo::find($this->equipoSeleccionado);
        $nombreTorneo = $this->campeonato->nombre;

        return Excel::download(
            new ListadoBuenaFeExport(
                $this->equipoSeleccionado,
                $nombreTorneo,
                $this->campeonatoId,
                $this->fechaJornada ?? $this->fecha
            ),
            'Fecha-' . ($this->fechaJornada ?? $this->fecha) . ' ' .  strtoupper(Str::slug($equipo->nombre)) . '.xlsx'
        );
    }

    public function exportarCampeonatoCompleto()
    {
        $nombreTorneo = $this->campeonato->nombre;

        return Excel::download(
            new CampeonatoCompletoExport(
                $this->campeonatoId,
                $nombreTorneo,
                $this->fecha
            ),
            'Campeonato-' . $this->fecha . '-' . strtoupper(Str::slug($nombreTorneo)) . '-COMPLETO.xlsx'
        );
    }

    // 🆕 Actualizado para incluir la jornada
    public function abrirPlanillaImprimible()
    {
        if (!$this->equipoSeleccionado) {
            LivewireAlert::title('Atención')
                ->text('Debe seleccionar un equipo primero')
                ->warning()
                ->toast()
                ->show();
            return;
        }

        return redirect()->route('planilla.imprimir', [
            'equipoId' => $this->equipoSeleccionado,
            'campeonatoId' => $this->campeonatoId,
            'jornada' => $this->fechaJornada ?? 'Sin jornada',
            'cancha' => $this->nombreCancha ?? ''
        ]);
    }

    // ... resto de métodos (actualizarSanciones, darDeBaja, etc.)
    public function actualizarSanciones()
    {
        $sanciones = Sanciones::where('cumplida', false)->get();

        foreach ($sanciones as $sancion) {
            $jugador = $sancion->jugador;
            $equipo = $jugador->equipo_id;

            $encuentros = Encuentro::where('campeonato_id', $sancion->campeonato_id)
                ->where('estado', 'Jugado')
                ->where('fecha_encuentro', '>', $sancion->fecha_sancion)
                ->where(function ($q) use ($equipo) {
                    $q->where('equipo_local_id', $equipo)
                        ->orWhere('equipo_visitante_id', $equipo);
                })
                ->orderBy('fecha_encuentro')
                ->get();

            $partidosCumplidos = $encuentros->count();

            $sancion->partidos_cumplidos = $partidosCumplidos;
            $sancion->cumplida = $partidosCumplidos >= $sancion->partidos_sancionados;
            $sancion->save();
        }

        $this->dispatch('actualizar-sancion');
    }

    public function darDeBaja($jugadorId)
    {
        $this->itemId = $jugadorId;

        LivewireAlert::title('Dar de Baja')
            ->text('Estas seguro de dar de baja el jugador?')
            ->asConfirm()
            ->onConfirm('bajaJugador', ['id' => $this->itemId])
            ->onDeny('keepItem', ['id' => $this->itemId])
            ->show();
    }

    public function keepItem($jugadorData)
    {
        // No hacer nada
    }

    public function bajaJugador($jugadorData)
    {
        $jugadorId = is_array($jugadorData) ? $jugadorData['id'] : $jugadorData;
        $equipoPorDefecto = DB::table('equipos')->where('nombre', 'Sin equipo')->first();

        if (!$equipoPorDefecto) {
            LivewireAlert::title('!Atención')
                ->text('Debe crear un equipo llamado "Sin equipo" antes de dar de baja.')
                ->error()
                ->toast()
                ->timer(5000)
                ->show();
            return;
        }

        $equipoId = $equipoPorDefecto->id;

        DB::table('campeonato_jugador_equipo')
            ->where('jugador_id', $jugadorId)
            ->whereNull('fecha_baja')
            ->update(['fecha_baja' => now()->toDateString()]);

        try {
            DB::table('campeonato_jugador_equipo')->insert([
                'jugador_id' => $jugadorId,
                'equipo_id' => $equipoId,
                'campeonato_id' => $this->campeonato->id,
                'categoria_id' => $this->campeonato->categoria_id,
                'fecha_alta' => now()->toDateString(),
                'fecha_baja' => null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error al insertar jugador de baja: " . $e->getMessage());
            LivewireAlert::title('Error')
                ->text('Ocurrió un error al intentar mover el jugador a "Sin equipo".')
                ->error()
                ->toast()
                ->timer(5000)
                ->show();
            return;
        }

        DB::table('jugadors')
            ->where('id', $jugadorId)
            ->update(['equipo_id' => $equipoId]);

        LivewireAlert::text('Correcto!')
            ->text('El jugador se dió de baja correctamente!')
            ->success()
            ->toast()
            ->position('top')
            ->show();
        $this->updatedEquipoSeleccionado($this->equipoSeleccionado);
    }

    public function render()
    {
        return view('livewire.equipo.listado-buena-fe-ver');
    }
}
