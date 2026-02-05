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

    public $campeonato;             // un solo campeonato
    public $campeonatoId;           // id recibido
    public $equiposDelCampeonato;   // equipos del campeonato
    public $equipoSeleccionado;     // id del equipo elegido
    public $jugadoresEquipos = []; // jugadores del equipo
    public $fecha;
    public $itemId;
    public $sanciones;

    public function mount($campeonatoId)
    {
        // Guardo el id
        $this->campeonatoId = $campeonatoId;



        // Cargo el campeonato con sus equipos
        $this->campeonato = Campeonato::with('equipos')->find($campeonatoId);

        // Carga y ordenamiento robusto: 
        // 1. Eliminamos la línea setRelation (es redundante aquí).
        // 2. Aplicamos la ordenación a la propiedad pública $equiposDelCampeonato,
        //    usando una función que ignora mayúsculas/minúsculas y espacios.
        $this->equiposDelCampeonato = $this->campeonato
            ? $this->campeonato->equipos->sortBy(fn($equipo) => strtoupper(trim($equipo->nombre)))
            : collect();
    }


    public function calcularPeriodoSancion($fechaInicio, $fechaFin)
    {

        // Validar que ambas fechas existan Y no estén vacías
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

    // Cuando se elige un equipo en el select
    public function updatedEquipoSeleccionado($equipoId)
    {

        if ($this->campeonatoId && $equipoId) {

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
                $this->fecha
            ),
            'Fecha-' . $this->fecha . ' ' .  strtoupper(Str::slug($equipo->nombre)) . '.xlsx'
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


    // Actualizar sanciones
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



    //DAR DE BAJA JUGADORES
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
        // No hacer nada, solo para manejar la negación
    }

    public function bajaJugador($jugadorData)
    {

        $jugadorId = is_array($jugadorData) ? $jugadorData['id'] : $jugadorData;
        // Buscar el equipo "Sin equipo"
        $equipoPorDefecto = DB::table('equipos')->where('nombre', 'Sin equipo')->first();

        if (!$equipoPorDefecto) {
            // Si no existe, mostrar mensaje y salir
            $this->dispatch('error', 'Debe crear un equipo llamado "Sin equipo" antes de dar de baja.');
            LivewireAlert::title('!Atención')
                ->text('Debe crear un equipo llamado "Sin equipo" antes de dar de baja.')
                ->error()
                ->toast()
                ->timer(5000)
                ->show();
            return;
        }


        $equipoId = $equipoPorDefecto->id;

        // Cerrar la relación actual en historial
        DB::table('campeonato_jugador_equipo')
            ->where('jugador_id', $jugadorId)
            ->whereNull('fecha_baja')
            ->update(['fecha_baja' => now()->toDateString()]);


        // Insertar nuevo registro en historial con el equipo por defecto
        try {
            DB::table('campeonato_jugador_equipo')->insert([
                'jugador_id' => $jugadorId,
                'equipo_id' => $equipoId,
                'campeonato_id' => $this->campeonato->id, // Ajusta esto según tu lógica
                'categoria_id' => $this->campeonato->categoria_id, // Ajusta esto según tu lógica
                'fecha_alta' => now()->toDateString(),
                'fecha_baja' => null,
            ]);
        } catch (\Exception $e) {
            // En lugar de dd(), que detiene el script, logueamos el error y mostramos una alerta.
            \Illuminate\Support\Facades\Log::error("Error al insertar jugador de baja: " . $e->getMessage());
            LivewireAlert::title('Error')
                ->text('Ocurrió un error al intentar mover el jugador a "Sin equipo".')
                ->error()
                ->toast()
                ->timer(5000)
                ->show();
            return; // Detener la ejecución si hay un error de DB
        }


        // Actualizar el jugador
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
