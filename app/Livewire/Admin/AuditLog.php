<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use OwenIt\Auditing\Models\Audit;

class AuditLog extends Component
{
    use WithPagination;

    public $search = '';

    // Reiniciar la paginación cuando se busca algo
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $audits = Audit::with(['user', 'auditable'])
            ->where(function($query) {
                $query->where('auditable_id', 'like', '%' . $this->search . '%')
                      ->orWhere('event', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.audit-log', [
            'audits' => $audits
        ]);
    }
}