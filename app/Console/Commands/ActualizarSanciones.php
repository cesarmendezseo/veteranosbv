<?php

namespace App\Console\Commands;

use App\Models\Sanciones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualizarSanciones extends Command
{
    protected $signature = 'sanciones:actualizar';
    protected $description = 'Actualiza las sanciones cada 7 días';

    public function handle()
    {
        $this->info('Actualizando sanciones...');

        // 1️⃣ Incrementar partidos cumplidos si aún no se cumplió la sanción
        Sanciones::where('cumplida', false)
            ->increment('partidos_cumplidos', 1);

        // 2️⃣ Marcar como cumplidas las sanciones que ya igualaron la cantidad
        Sanciones::whereColumn('partidos_cumplidos', '>=', 'partidos_sancionados')
            ->update(['cumplida' => true]);

        $this->info('Sanciones actualizadas correctamente.');
    }
}
