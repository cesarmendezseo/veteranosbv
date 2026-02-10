<?php

namespace App\Livewire\Admin;


use Livewire\Component;
use Livewire\WithPagination;
use OwenIt\Auditing\Models\Audit;

class AuditLog extends Component
{
    use WithPagination;

    public function render()
    {
        // Traemos las auditorías paginadas y cargamos el usuario
        $audits = Audit::with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.audit-log', [
            'audits' => $audits
        ]);
    }
    
}
