<?php

namespace App\Http\Livewire;
use App\Models\CodigoBarras;

use Livewire\Component;

class Dashboard extends Component
{
    public $busqueda;
    public $codigosGenerados;

    public function getCodigosGenerados()
{
    $query = CodigoBarras::where('usuario_id', auth()->id())
        ->latest('created_at');

    if ($this->busqueda) {
        $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
    }

    $this->codigosGenerados = $query->paginate(12);

    return view('livewire.dashboard', ['codigosGenerados' => $this->codigosGenerados]);
}

    public function render()
    {
        return view('livewire.dashboard');
    }
}
