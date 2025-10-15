<?php

namespace App\Livewire\Config;

use App\Models\CampeonatoJugadorEquipo;
use App\Models\Jugador;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CopiarListadoBuenaFe extends Component
{

    public $campeonato_id; //campeoanto orignen
    public $campeonato_id2; //Campeonato destino
    public $campeonatos; //esete se va usar cuandi se empieza por primera vez el sistema y unicamente esa vez
    public $equiposOrigen = []; // lista de equipos del campeonato origen
    public $equiposSeleccionados = []; // equipos elegidos para copiar

    public function copiar()
    {
        if (!$this->campeonato_id) {


            LivewireAlert::title('alert')
                ->text('Debe seleccionar un campeonato al que quiere copiar')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        if (!$this->campeonato_id2) {


            LivewireAlert::title('alert')
                ->text('Debe seleccionar un campeonato del cual va a copiar')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        $categoria_id = DB::table('campeonatos')
            ->where('id', $this->campeonato_id)
            ->value('categoria_id');
        // Obtiene los jugadores
        $jugadores = CampeonatoJugadorEquipo::select('jugador_id', 'equipo_id', 'categoria_id')
            ->where('campeonato_id', $this->campeonato_id2) // o $campeonatoId si estás fuera del componente
            ->get();

        // Filtra los que no estén ya en la tabla pivote
        $existentes = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonato_id)
            ->pluck('jugador_id')
            ->toArray();

        $nuevos = $jugadores->reject(fn($j) => in_array($j->id, $existentes))
            ->map(fn($j) => [
                'jugador_id' => $j->id,
                'equipo_id' => $j->equipo_id,
                'campeonato_id' => $this->campeonato_id,
                'categoria_id' => $categoria_id,
                'fecha_alta' => now(),
            ])->toArray();

        if (empty($nuevos)) {

            LivewireAlert::title('Info')
                ->text('Todos los jugadores ya están asignados.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        // Inserta en bloque
        DB::table('campeonato_jugador_equipo')->insert($nuevos);


        LivewireAlert::title('Info')
            ->text('jugadores asignados correctamente.')
            ->toast()
            ->position('top-end')
            ->info()
            ->show();
    }

    public function updatedCampeonatoId($value)
    {

        if ($value) {
            $this->equiposOrigen = \App\Models\Equipo::whereHas('campeonatos', function ($q) use ($value) {
                $q->where('campeonato_id', $value);
            })
                ->orderBy('nombre')
                ->get();
        } else {
            $this->equiposOrigen = collect();
        }
    }

    public function duplicarListado()
    {
        if (!$this->campeonato_id || !$this->campeonato_id2) {
            LivewireAlert::title('Info')
                ->text('Debe seleccionar ambos campeonatos.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        if (empty($this->equiposSeleccionados)) {
            LivewireAlert::title('Info')
                ->text('Debe seleccionar al menos un equipo para copiar.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        // ⚙️ 1. Obtener todos los jugadores del campeonato origen solo de esos equipos
        $origen = \App\Models\CampeonatoJugadorEquipo::select('jugador_id', 'equipo_id', 'categoria_id')
            ->where('campeonato_id', $this->campeonato_id)
            ->whereIn('equipo_id', $this->equiposSeleccionados)
            ->whereNull('fecha_baja')
            ->get();

        if ($origen->isEmpty()) {
            LivewireAlert::title('Info')
                ->text('Los equipos seleccionados no tienen jugadores activos en el campeonato origen.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        // ⚙️ 2. Jugadores ya existentes en el campeonato destino
        $existentes = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonato_id2)
            ->pluck('jugador_id')
            ->toArray();

        // ⚙️ 3. Filtrar solo los nuevos
        $nuevos = $origen->reject(fn($j) => in_array($j->jugador_id, $existentes))
            ->map(fn($j) => [
                'jugador_id'     => $j->jugador_id,
                'equipo_id'      => $j->equipo_id,
                'categoria_id'   => $j->categoria_id,
                'campeonato_id'  => $this->campeonato_id2,
                'fecha_alta'     => now(),
            ]);

        if ($nuevos->isEmpty()) {
            LivewireAlert::title('Info')
                ->text('Todos los jugadores ya existen en el campeonato destino.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        // ⚙️ 4. Insertar en bloque
        DB::table('campeonato_jugador_equipo')->insert($nuevos->toArray());

        LivewireAlert::title('Éxito')
            ->text('Jugadores copiados correctamente al campeonato destino.')
            ->toast()
            ->position('top-end')
            ->success()
            ->show();
    }

    public function duplicarListado1()
    {
        if (!$this->campeonato_id2) {
            LivewireAlert::title('Info')
                ->text('Debe seleccionar el campeonato destino.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        if (!$this->campeonato_id) {
            LivewireAlert::title('Info')
                ->text('Debe seleccionar el campeonato origen.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }



        // ⚙️ 1. Obtiene los registros del campeonato ORIGEN
        $origen = \App\Models\CampeonatoJugadorEquipo::select('jugador_id', 'equipo_id', 'categoria_id')
            ->where('campeonato_id', $this->campeonato_id)
            ->get();



        if ($origen->isEmpty()) {

            LivewireAlert::title('Info')
                ->text('El campeonato origen no tiene jugadores asignados.')
                ->toast()
                ->position('top-end')
                ->info()
                ->show();
            return;
        }

        // ⚙️ 2. Obtiene los jugadores ya existentes en el campeonato DESTINO
        $existentes = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonato_id2)
            ->pluck('jugador_id')
            ->toArray();


        // ⚙️ 3. Filtra solo los nuevos jugadores
        $nuevos = $origen->reject(fn($j) => in_array($j->jugador_id, $existentes))
            ->map(fn($j) => [
                'jugador_id'     => $j->jugador_id,
                'equipo_id'      => $j->equipo_id,
                'categoria_id'   => $j->categoria_id,
                'campeonato_id'  => $this->campeonato_id2,
                'fecha_alta'     => now(),
            ]);



        if ($nuevos->isEmpty()) {

            LivewireAlert::title('Info')
                ->text('Todos los jugadores del campeonato origen ya están en el destino.')
                ->toast()
                ->position('center')
                ->info()
                ->show();
            return;
        }

        // ⚙️ 4. Inserta en bloque
        DB::table('campeonato_jugador_equipo')->insert($nuevos->toArray());


        LivewireAlert::title('Info')
            ->text('Jugadores copiados correctamente al campeonato destino.')
            ->toast()
            ->position('top-end')
            ->info()
            ->show();
    }
    public function render()
    {
        return view('livewire.config.copiar-listado-buena-fe');
    }
}
