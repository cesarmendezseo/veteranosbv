<?php

namespace App\Listeners;
use Spatie\Permission\Events\RoleAssigned;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuditRoleAssignment
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle( $event): void
    {
        $eventoNombre = ($event instanceof RoleAssigned) ? 'Rol Asignado' : 'Rol Quitado';
        // Creamos un registro manual en la tabla de auditoría
        Audit::create([
            'user_type'    => \App\Models\User::class,
            'user_id'      => Auth::id(), // Quién lo asignó
            'event'        => 'updated',
            'auditable_type' => get_class($event->model), // El usuario que recibió el rol
            'auditable_id'   => $event->model->id,
            'old_values'   => [],
            'new_values'   => ['role' => $event->role->name],
            'url'          => request()->fullUrl(),
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'tags'         => 'role_assigned',
            'event'      => 'updated',
        'new_values' => [
            'accion' => $eventoNombre,
            'role'   => $event->role->name,
        ],  
        ]);
    }
}
