<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Response;
use Livewire\WithFileUploads;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use App\Models\CodigoBarras;

class CodigoBarrasGenerator extends Component
{
    use WithFileUploads;

    public $codigoGenerado;
    public $numeroCodigo;

    public function generarCodigo()
    {
        do {
            $nuevoCodigo = strval(mt_rand(100000, 999999));
        } while (CodigoBarras::where('codigo_barras', $nuevoCodigo)->exists());

        $this->numeroCodigo = $nuevoCodigo;

        $generator = new BarcodeGeneratorPNG();
        $code2 = str_replace(' ', '', $nuevoCodigo);

        $imgData = $generator->getBarcode($code2, 'C128');
        $base64Image = 'data:image/png;base64,' . base64_encode($imgData);

        // Guardar la imagen temporalmente (puedes ajustar esto según tus necesidades)
        $nombreArchivo = 'codigo_barras_temp.png';
        Storage::disk('public')->put($nombreArchivo, $imgData);

        $this->codigoGenerado = $base64Image;

        // Guardar el código en la base de datos
        $codigoBarras = new CodigoBarras([
            'codigo_barras' => $nuevoCodigo,
            'usuario_id' => auth()->id(),
        ]);
        $codigoBarras->save();
    }

    public function descargarCodigo()
    {
        if ($this->codigoGenerado) {
            $archivo = public_path('storage/codigo_barras_temp.png');
            return response()->download($archivo, 'codigo_barras_temp.png', ['Content-Disposition' => 'attachment']);
        }
    }

        public function getCodigosGenerados()
    {
        $codigosGenerados = CodigoBarras::all();
        return $codigosGenerados;
    }

    public function render()
    {
        $codigosGenerados = $this->getCodigosGenerados();
        return view('livewire.codigo-barras-generator', compact('codigosGenerados'));
    }

}

