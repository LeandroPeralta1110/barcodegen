<?php

namespace App\Http\Livewire;
use App\Models\CodigoBarras;

use Livewire\Component;

class Dashboard extends Component
{
    protected $codigosGenerados = [];
    protected $codigosGeneradosAdmin = [];
    public $busqueda;
    public $page;

    public function buscar()
    {
        $this->resetPage(); // Reinicia la paginación al realizar una nueva búsqueda
    }
    
    public function getCodigosGenerados()
    {
        $query = CodigoBarras::where('usuario_id', auth()->id())
            ->latest('created_at');
    
        if (!empty($this->busqueda)) {
            $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
        }
    
        $this->codigosGenerados = $query->paginate(12, ['*'], 'page', $this->page);
    }

    public function getCodigosGeneradosAdmin()
{
    $query = CodigoBarras::with('usuario') // Cargar la relación 'usuario' para obtener información del usuario
        ->latest('created_at');

    if (!empty($this->busqueda)) {
        $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
    }

    $this->codigosGeneradosAdmin = $query->paginate(12, ['*'], 'page', $this->page);
}

    public function getPropiedadCodigosGenerados()
    {
        return $this->codigosGenerados;
    }
    
    public function render()
    {
        $this->getCodigosGenerados(); // Llama a la función para obtener los códigos generados
        $this->getCodigosGeneradosAdmin();
        return view('livewire.dashboard', ['codigosGenerados' => $this->codigosGenerados, 'codigosGeneradosAdmin' => $this->codigosGeneradosAdmin]);
    }
    
}
