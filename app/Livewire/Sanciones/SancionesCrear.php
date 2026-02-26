<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\Jugador;
use App\Models\Sanciones;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class SancionesCrear extends Component
{
    // Propiedades de búsqueda y selección
    public $campeonatoId;
    public $jugador_id;
    public $nombreJugador;
    public $buscarJugador;
    public $jugadores = [];
    public $jugadorSeleccionado = null;

    // Propiedades del Partido/Contexto
    public $partido_id;
    public $partido_tipo;
    public $partidosDisponibles = [];
    public $fechaBuscada;
    public $opcionesFechaFase = [];
    public $partidoJugadorInfo = null;

    // Propiedades de la Sanción
    public $tipo_medida = 'partidos'; // 'partidos' o 'tiempo'
    public $partidos_sancionados = 1;
    public $fecha_fin; // Para sanciones de 1 año, 2 años, etc.
    public $motivo;
    public $observacion;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $campeonato = Campeonato::find($campeonatoId);

        if (!$campeonato) return redirect()->route('campeonatos.index');

        $this->partido_tipo = in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::class
            : Eliminatoria::class;

        $this->cargarOpcionesFechaFase();
    }

    public function cargarOpcionesFechaFase()
    {
        $ordenFases = ['treintaydosavo', 'diesoseisavo', 'octavos', 'cuartos', 'semifinal', '3er y 4to', 'final'];
        $campeonato = Campeonato::find($this->campeonatoId);

        if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
            $this->opcionesFechaFase = Encuentro::where('campeonato_id', $this->campeonatoId)
                ->pluck('fecha_encuentro')
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        } else {
            $this->opcionesFechaFase = Eliminatoria::where('campeonato_id', $this->campeonatoId)
                ->pluck('fase')
                ->unique()
                ->filter()
                ->sortBy(fn($fase) => array_search(strtolower($fase), $ordenFases))
                ->values()
                ->toArray();
        }
    }

    public function buscarJugadorSancion()
    {
        if (!empty($this->buscarJugador)) {
            $buscar = trim($this->buscarJugador);

            $this->jugadores = Jugador::with(['equiposPorCampeonato' => function ($query) {
                // AQUÍ ESTÁ EL CAMBIO: Especificamos la tabla pivote
                $query->where('campeonato_jugador_equipo.campeonato_id', $this->campeonatoId);
            }])
                ->where(function ($query) use ($buscar) {
                    // ... (tu lógica de búsqueda por nombre/apellido)
                    $query->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido', 'like', "%{$buscar}%")
                        ->orWhere('documento', 'like', "%{$buscar}%");
                })
                ->get();
        } else {
            $this->jugadores = [];
        }
    }

    public function agregarJugador($jugadorId)
    {
        $this->jugadorSeleccionado = Jugador::with(['equiposPorCampeonato' => function ($q) {
            // AQUÍ TAMBIÉN: Especificamos la tabla pivote
            $q->where('campeonato_jugador_equipo.campeonato_id', $this->campeonatoId);
        }])->find($jugadorId);

        $this->jugador_id = $jugadorId;

        $equipoDelCampeonato = $this->jugadorSeleccionado->equiposPorCampeonato->first();
        $nombreEquipo = $equipoDelCampeonato ? $equipoDelCampeonato->nombre : 'Sin equipo';

        $this->nombreJugador = $this->jugadorSeleccionado->nombreCompleto . ' - ' . $nombreEquipo;
        $this->buscarJugador = "";
        $this->jugadores = [];
    }

    public function updatedFechaBuscada()
    {
        $this->buscarPartidoDelJugador();
    }

    public function buscarPartidoDelJugador()
    {
        if (!$this->jugador_id || !$this->fechaBuscada) return;

        // Buscar el equipo del jugador EN ESTE campeonato específico
        $fila = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->where('jugador_id', $this->jugador_id)
            ->first(); // traemos toda la fila para poder debuggear mejor

        if (!$fila || !$fila->equipo_id) {
            $this->partidoJugadorInfo = 'El jugador no tiene equipo asignado en este campeonato.';
            $this->partido_id = null;
            return;
        }

        $equipoId = $fila->equipo_id;

        $query = $this->partido_tipo::with(['equipoLocal', 'equipoVisitante'])
            ->where('campeonato_id', $this->campeonatoId) // ← asegurarse que sea el campeonato correcto
            ->where(function ($q) use ($equipoId) {
                $q->where('equipo_local_id', $equipoId)
                    ->orWhere('equipo_visitante_id', $equipoId);
            });

        if ($this->partido_tipo === Encuentro::class) {
            $query->where('fecha_encuentro', $this->fechaBuscada);
        } else {
            $query->where('fase', $this->fechaBuscada);
        }

        $partido = $query->first();

        if ($partido) {
            $this->partidoJugadorInfo = "{$partido->equipoLocal->nombre} vs {$partido->equipoVisitante->nombre}";
            $this->partido_id = $partido->id;
        } else {
            $this->partidoJugadorInfo = 'No se encontró partido en esta fecha/fase.';
            $this->partido_id = null;
        }
    }

    // Atajos para el tribunal
    public function setTiempo($cantidad, $unidad)
    {
        $this->tipo_medida = 'tiempo';
        $this->fecha_fin = now()->add($cantidad, $unidad)->format('Y-m-d');
    }

    public function guardar()
    {

        $rules = [
            'jugador_id' => 'required|exists:jugadors,id',
            'partido_id' => 'nullable', // <--- Esto es lo que está fallando según tu dd()
            'motivo' => 'required|string',
            'tipo_medida' => 'required|in:partidos,tiempo',
        ];

        if ($this->tipo_medida === 'partidos') {
            $rules['partidos_sancionados'] = 'required|integer|min:1';
        } else {
            $rules['fecha_fin'] = 'required|date|after:today';
        }

        // Mensajes personalizados para que el usuario entienda el error
        $messages = [
            'partido_id.required' => 'No se puede cargar la sanción: el jugador no tiene un partido asignado en la fecha seleccionada.',
            'jugador_id.required' => 'Debes seleccionar un jugador.',
            'fecha_fin.required' => 'Debes ingresar la fecha de finalización.',
        ];

        try {
            $this->validate($rules, $messages);
            // Verificar duplicados activos
            $pendiente = Sanciones::where('jugador_id', $this->jugador_id)
                ->where('campeonato_id', $this->campeonatoId)
                ->where('cumplida', false)
                ->exists();

            if ($pendiente) {

                LivewireAlert::title('Atención')
                    ->text('El jugador ya posee una sanción activa.')
                    ->warning()
                    ->show();
                return;
            }

            Sanciones::create([
                'jugador_id' => $this->jugador_id,
                'campeonato_id' => $this->campeonatoId,
                'etapa_sancion' => $this->fechaBuscada, // O now() según prefieras
                'motivo' => $this->motivo,
                'observacion' => $this->observacion,
                'partidos_sancionados' => $this->tipo_medida === 'partidos' ? $this->partidos_sancionados : 0,
                'fecha_inicio' => Carbon::today(),
                'fecha_fin' => $this->tipo_medida === 'tiempo' ? $this->fecha_fin : null,
                'medida' => $this->tipo_medida,
                'sancionable_id' => $this->partido_id,
                'sancionable_type' => $this->partido_tipo,
                'partidos_cumplidos' => 0,
                'cumplida' => false,
            ]);


            LivewireAlert::title('Éxito')
                ->text('Sanción registrada correctamente.')
                ->success()
                ->show();
            $this->reset([
                'jugador_id',
                'nombreJugador',
                'buscarJugador',      // <--- Importante
                'jugadores',         // <--- Importante (limpia la lista de búsqueda)
                'jugadorSeleccionado',
                'partido_id',
                'fechaBuscada',      // <--- Importante (resetea el select de fecha/fase)
                'motivo',
                'observacion',
                'partidoJugadorInfo',
                'partidos_sancionados', // <--- Volver a 1 por defecto
                'fecha_fin'
            ]);

            // Opcional: Si quieres que el tipo de medida vuelva a 'partidos'
            $this->tipo_medida = 'partidos';
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Obtenemos el primer mensaje de error de la validación
            $primerError = collect($e->errors())->flatten()->first();

            LivewireAlert::title('Error de Validación')
                ->text($primerError)
                ->error()
                ->toast()
                ->withConfirmButton('ok')
                ->show();


            throw $e; // Importante para que Livewire pinte los errores en el Blade si usas @error
        }
    }

    public function actualizarCumplimientosSanciones()
    {
        $hoy = Carbon::today();

        Sanciones::where('cumplida', false)->chunk(50, function ($sanciones) use ($hoy) {
            foreach ($sanciones as $sancion) {
                // Caso TIEMPO
                if ($sancion->medida === 'tiempo' || $sancion->fin_sancion_at) {
                    if ($hoy->greaterThan($sancion->fin_sancion_at)) {
                        $sancion->update(['cumplida' => true]);
                    }
                    continue;
                }

                // Caso PARTIDOS (Tu lógica original)
                $equipoId = DB::table('campeonato_jugador_equipo')
                    ->where('campeonato_id', $sancion->campeonato_id)
                    ->where('jugador_id', $sancion->jugador_id)
                    ->value('equipo_id');

                if (!$equipoId) continue;

                $count = Encuentro::where('campeonato_id', $sancion->campeonato_id)
                    ->where('estado', 'Jugado')
                    ->where('fecha_encuentro', '>', $sancion->fecha_sancion)
                    ->where(function ($q) use ($equipoId) {
                        $q->where('equipo_local_id', $equipoId)->orWhere('equipo_visitante_id', $equipoId);
                    })->count();

                $sancion->update([
                    'partidos_cumplidos' => $count,
                    'cumplida' => $count >= $sancion->partidos_sancionados
                ]);
            }
        });

        $this->alert('success', 'Sanciones actualizadas');
        LivewireAlert::title('Éxito')
            ->text('Sanciones actualizadas correctamente.')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.sanciones.sanciones-crear');
    }
}
