<?php

namespace App\Console\Commands;

use App\Models\Sanciones;
use App\Models\User;
use App\Notifications\SancionesActualizadas;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class ActualizarSanciones extends Command
{
    protected $signature = 'sanciones:actualizar';
    protected $description = 'Actualiza las sanciones cada 7 días';

    public function handle()
    {
        $this->info('Actualizando sanciones...');

        // 1️⃣ Incrementar partidos cumplidos solo si aún no alcanzó el límite
        Sanciones::where('cumplida', false)
            ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
            ->increment('partidos_cumplidos', 1);

        // 2️⃣ Marcar como cumplidas las sanciones que ya igualaron la cantidad
        Sanciones::where('cumplida', false)
            ->whereColumn('partidos_cumplidos', '>=', 'partidos_sancionados')
            ->update(['cumplida' => true]);

        // Enviar notificación al admin (ajusta el ID o el criterio)
        $emailAdmin = \App\Models\Configuracion::where('key', 'email_notificaciones')->value('value')
            ?? 'cesarmendez.seo@gmail.com';
        $admin = User::where('email', $emailAdmin)->first(); // o User::where('role', 'admin')->first();
        Notification::send($admin, new SancionesActualizadas());

        $this->info('Sanciones actualizadas correctamente.');
    }
}
