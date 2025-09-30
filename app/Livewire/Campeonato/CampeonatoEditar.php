<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use Livewire\Component;
use App\Models\Criterios_desempate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use SweetAlert2\Laravel\Swal; // Asegúrate de que este namespace es correcto, puede ser solo \Illuminate\Support\Facades\Session para mensajes flash estándar.

class CampeonatoEditar extends Component
{
    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $cantidad_equipos_grupo; // Este parece ser un campo redundante en tu lógica actual, usaremos $equipos_por_grupo
    public $puntos_victoria;
    public $puntos_empate;
    public $puntos_derrota;
    public $puntos_tarjeta_amarilla;
    public $puntos_doble_amarilla;
    public $puntos_tarjeta_roja;
    public $total_equipos;
    public $equipos_por_grupo;
    public $categoria_id;
    public $categorias;
    public $criterios;
    public $campeonatoId;
    // public $Criterios_desempate; // Este no es necesario como propiedad pública

    protected $listeners = ['actualizarVista' => '$refresh']; // Listener opcional para refrescar si es necesario

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $campeonato = Campeonato::with('grupos', 'categoria', 'criterioDesempate')
            ->findOrFail($campeonatoId);

        $this->nombre = $campeonato->nombre;
        $this->formato = $campeonato->formato;
        $this->cantidad_grupos = $campeonato->cantidad_grupos;
        // Asignación correcta
        $this->equipos_por_grupo = $campeonato->cantidad_equipos_grupo;

        $this->puntos_victoria = $campeonato->puntos_ganado;
        $this->puntos_empate = $campeonato->puntos_empatado;
        $this->puntos_derrota = $campeonato->puntos_perdido;
        $this->puntos_tarjeta_amarilla = $campeonato->puntos_tarjeta_amarilla;
        $this->puntos_doble_amarilla = $campeonato->puntos_doble_amarilla;
        $this->puntos_tarjeta_roja = $campeonato->puntos_tarjeta_roja;

        $this->categoria_id = $campeonato->categoria_id;
        $this->categorias = Categoria::all();

        // Cargar total_equipos: si es 'todos_contra_todos' o eliminacion, se carga de la tabla principal
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'eliminacion_simple' || $this->formato === 'eliminacion_doble') {
            $this->total_equipos = $campeonato->cantidad_equipos;
        } else {
            // Tu lógica anterior no era clara, pero para edición, total_equipos ya debería estar en la tabla
            $this->total_equipos = $campeonato->cantidad_equipos;
        }

        // Si querés guardar los criterios para usar en la vista también:
        $this->criterios = $campeonato->criterioDesempate->sortBy('orden')->all();
    }


    /**
     * Define las reglas de validación condicionales.
     */
    protected function rules()
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'formato' => 'required|string|in:todos_contra_todos,grupos,eliminacion_simple,eliminacion_doble',
            'categoria_id' => 'required|exists:categorias,id',

            // Reglas para Fair Play (son necesarias para todos los formatos)
            'puntos_tarjeta_amarilla' => 'required|integer|min:-100|max:100',
            'puntos_doble_amarilla' => 'required|integer|min:-100|max:100',
            'puntos_tarjeta_roja' => 'required|integer|min:-100|max:100',
        ];

        // Reglas CONDICIONALES según el formato
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'eliminacion_simple' || $this->formato === 'eliminacion_doble') {
            // Se requiere el total de equipos
            $rules['total_equipos'] = 'required|integer|min:2';
        }

        if ($this->formato === 'grupos') {
            // Se requieren grupos y equipos por grupo
            $rules['cantidad_grupos'] = 'required|integer|min:1';
            $rules['equipos_por_grupo'] = 'required|integer|min:2';
        }

        // Puntos solo se requieren para formatos de LIGA
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'grupos') {
            $rules['puntos_victoria'] = 'required|integer|min:0';
            $rules['puntos_empate'] = 'required|integer|min:0';
            $rules['puntos_derrota'] = 'required|integer|min:0';
        }

        return $rules;
    }


    public function moveCriterioUp($index)
    {
        if ($index > 0) {
            $temp = $this->criterios[$index - 1];
            $this->criterios[$index - 1] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }

    public function moveCriterioDown($index)
    {
        if ($index < count($this->criterios) - 1) {
            $temp = $this->criterios[$index + 1];
            $this->criterios[$index + 1] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }


    public function editar()
    {
        // 1. Validar usando las reglas condicionales
        $this->validate();

        $campeonato = Campeonato::findOrFail($this->campeonatoId);

        // 2. Determinar los valores por defecto/seguros (usando 0 en lugar de null)
        $data = [
            'nombre' => $this->nombre,
            'formato' => $this->formato,
            'categoria_id' => $this->categoria_id,
            'puntos_tarjeta_amarilla' => $this->puntos_tarjeta_amarilla,
            'puntos_doble_amarilla' => $this->puntos_doble_amarilla,
            'puntos_tarjeta_roja' => $this->puntos_tarjeta_roja,

            // **IMPORTANTE: Usamos 0 para campos NOT NULL cuando no son relevantes**
            'cantidad_grupos' => 0,
            'cantidad_equipos_grupo' => 0,
            'puntos_ganado' => 0,
            'puntos_empatado' => 0,
            'puntos_perdido' => 0,
            'cantidad_equipos' => 0, // Usar 0 en lugar de null si 'cantidad_equipos' también es NOT NULL
        ];

        // 3. Sobreescribir valores basados en el formato

        // Si es formato de liga o eliminación simple/doble
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'eliminacion_simple' || $this->formato === 'eliminacion_doble') {
            $data['cantidad_equipos'] = $this->total_equipos;
        }

        // Si es formato de grupos
        if ($this->formato === 'grupos') {
            $data['cantidad_grupos'] = $this->cantidad_grupos;
            $data['cantidad_equipos_grupo'] = $this->equipos_por_grupo;
            $data['cantidad_equipos'] = $this->cantidad_grupos * $this->equipos_por_grupo;
        }

        // Si es formato de liga (todos_contra_todos o grupos)
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'grupos') {
            $data['puntos_ganado'] = $this->puntos_victoria;
            $data['puntos_empatado'] = $this->puntos_empate;
            $data['puntos_perdido'] = $this->puntos_derrota;
        }

        // 4. Actualizar el campeonato
        $campeonato->update($data);

        // 5. Actualizar criterios de desempate (Solo si es liga o grupos)
        if ($this->formato === 'todos_contra_todos' || $this->formato === 'grupos') {
            // ... (Tu lógica para guardar criterios) ...
            Criterios_desempate::where('campeonato_id', $campeonato->id)->delete();
            foreach ($this->criterios as $index => $criterio) {
                Criterios_desempate::create([
                    'campeonato_id' => $campeonato->id,
                    'criterio' => is_array($criterio) ? $criterio['criterio'] : $criterio->criterio,
                    'orden' => $index + 1,
                ]);
            }
        } else {
            // Eliminar criterios si el formato es de eliminación
            Criterios_desempate::where('campeonato_id', $campeonato->id)->delete();
        }


        // Flash message to indicate success
        LivewireAlert::title('Ok')
            ->text('Campeonato actualizado correctamente')
            ->success()
            ->toast()
            ->position('center')
            ->show();

        return redirect()->route('campeonato.index');
    }





    public function render()
    {
        return view('livewire.campeonato.campeonato-editar');
    }
}
