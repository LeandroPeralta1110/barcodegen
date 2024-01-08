<?php

namespace App\Http\Controllers;

use App\Models\CodigoBarras;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public $busqueda;
    
    public function welcome()
{
    $datosGrafico = $this->getCodigosGeneradosPorUnidadDeNegocio();
    /* dd($datosGrafico); */
    return view('welcome', compact('datosGrafico'));
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

public function getCodigosGeneradosPorUnidadDeNegocio()
{
    $codigosGeneradosAdmin = $this->getCodigosGeneradosAdmin();

    $datosPorUnidadNegocio = [];

    foreach ($codigosGeneradosAdmin as $codigoGenerado) {
        $unidadNegocio = optional($codigoGenerado->usuario->sucursal)->nombre ?? 'Sin Unidad de Negocio';
        $cantidad = 1; // Cada código de barras cuenta como 1 en este caso.
        $fechaGeneracion = $codigoGenerado->created_at->format('Y-m-d'); // Obtén la fecha de generación del código.

        // Asegurémonos de que estemos utilizando el campo correcto
        if ($unidadNegocio === 'Sin Unidad de Negocio') {
            $unidadNegocio = optional($codigoGenerado->usuario->sucursal)->nombre_unidad_negocio ?? 'Sin Unidad de Negocio';
        }

        // Verifica si ya existe una entrada para la unidad de negocio y la fecha.
        if (!isset($datosPorUnidadNegocio[$unidadNegocio][$fechaGeneracion])) {
            $datosPorUnidadNegocio[$unidadNegocio][$fechaGeneracion] = $cantidad;
        } else {
            $datosPorUnidadNegocio[$unidadNegocio][$fechaGeneracion] += $cantidad;
        }
    }

    return $datosPorUnidadNegocio;
}
}
