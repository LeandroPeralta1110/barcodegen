<?php

namespace App\Http\Livewire;

use App\Models\CodigoBarras;
use Livewire\Component;

class Dashboard extends Component
{
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

        return $query->paginate(12, ['*'], 'page', $this->page);
    }

    public function getCodigosGeneradosAdminPaginados(){
        $query = CodigoBarras::with(['usuario.sucursal', 'product'])
            ->latest('created_at');

        if (!empty($this->busqueda)) {
            $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
        }

        return $query->paginate(12, ['*'], 'page', $this->page);
    }

    public function getCodigosGeneradosAdmin()
    {
        $query = CodigoBarras::with(['usuario.sucursal', 'product'])
            ->latest('created_at');

        if (!empty($this->busqueda)) {
            $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
        }

        return $query->get();
    }

    public function getDatosGraficosTodosProductos()
    {
        $codigosGeneradosAdmin = $this->getCodigosGeneradosAdmin();

        $datosTodosProductos = [];

        foreach ($codigosGeneradosAdmin as $codigoGenerado) {
            $sucursal = optional($codigoGenerado->usuario->sucursal)->nombre ?? 'Sin Sucursal';
            $productoNombre = optional($codigoGenerado->product)->nombre ?? 'Sin Producto';

            if (!isset($datosTodosProductos[$sucursal][$productoNombre])) {
                $datosTodosProductos[$sucursal][$productoNombre] = 1;
            } else {
                $datosTodosProductos[$sucursal][$productoNombre]++;
            }
        }

        return $datosTodosProductos;
    }

    public function render()
    {
        $codigosGenerados = $this->getCodigosGenerados();
        $codigosGeneradosAdmin = $this->getCodigosGeneradosAdmin();
        $datosTodosProductos = $this->getDatosGraficosTodosProductos();
        $codigosGeneradosAdminPaginados= $this->getCodigosGeneradosAdminPaginados();

        return view('livewire.dashboard', [
            'codigosGenerados' => $codigosGenerados,
            'codigosGeneradosAdminPaginados' => $codigosGeneradosAdminPaginados,
            'codigosGeneradosAdmin' => $codigosGeneradosAdmin,
            'datosTodosProductos' => $datosTodosProductos,
        ]);
    }
}
