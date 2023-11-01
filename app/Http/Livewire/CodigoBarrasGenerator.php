<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Response;
use Livewire\WithFileUploads;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use App\Models\CodigoBarras;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Image;
use Dompdf\Dompdf;
use Dompdf\Options;

class CodigoBarrasGenerator extends Component
{
    use WithFileUploads;

    public $codigoGenerado;
    public $numeroCodigo;
    public $tipoCodigoBarras = 'C128'; // Valor predeterminado

    public function generarCodigo()
    {
        do {
            $nuevoCodigo = strval(mt_rand(100000, 999999));
        } while (CodigoBarras::where('codigo_barras', $nuevoCodigo)->exists());
    
        $this->numeroCodigo = $nuevoCodigo;
    
        $generator = new BarcodeGeneratorPNG();
        $code2 = str_replace(' ', '', $nuevoCodigo);
    
        // Obtener la imagen del código de barras
        $imgData = $generator->getBarcode($code2, $this->tipoCodigoBarras);
        $img = imagecreatefromstring($imgData);
    
        // Calcular el ancho y alto de la imagen del código de barras
        $anchoCodigo = imagesx($img);
        $altoCodigo = imagesy($img);
    
        // Espaciado entre los números y el código de barras
        $espaciadoNumeros = 10;
    
        // Calcular el ancho total
        $anchoDeseado = $anchoCodigo;
        $altoDeseado = $altoCodigo + $espaciadoNumeros + 40; // Agregar espacio para el número
    
        // Crear la imagen combinada con el nuevo tamaño
        $combinedImg = imagecreatetruecolor($anchoDeseado, $altoDeseado);
    
        // Crear un color blanco para el fondo
        $colorFondo = imagecolorallocate($combinedImg, 255, 255, 255);
    
        // Rellenar el fondo con blanco
        imagefilledrectangle($combinedImg, 0, 0, $anchoDeseado, $altoDeseado, $colorFondo);
    
        // Calcular la posición donde se copiará el código de barras en la imagen (centrado horizontalmente)
        $xCodigo = ($anchoDeseado - $anchoCodigo) / 2;
        $yCodigo = 0;
    
        // Copiar el código de barras en la imagen combinada
        imagecopy($combinedImg, $img, $xCodigo, $yCodigo, 0, 0, $anchoCodigo, $altoCodigo);
    
        // Crear un color negro para el número de código
        $colorTexto = imagecolorallocate($combinedImg, 0, 0, 0);
    
        // Calcular la posición donde se agregarán los números de código (centrado horizontalmente y debajo del código)
        $font_size = 20; // Tamaño de fuente
        $xNumero = ($anchoDeseado - imagefontwidth($font_size) * strlen($nuevoCodigo)) / 2;
        $yNumero = $altoCodigo + $espaciadoNumeros; // Espacio entre el código y el número
    
        // Agregar el número de código de barras como texto en la imagen combinada
        imagestring($combinedImg, $font_size, $xNumero, $yNumero, $nuevoCodigo, $colorTexto);
    
        // Generar la imagen combinada en tiempo real y mostrarla en la vista
        ob_start();
        imagepng($combinedImg);
        $imagenCombinada = 'data:image/png;base64,' . base64_encode(ob_get_clean());
        imagedestroy($combinedImg);
    
        $this->codigoGenerado = $imagenCombinada;
        
        // Guardar el código en la base de datos
        $codigoBarras = new CodigoBarras([
            'codigo_barras' => $nuevoCodigo,
            'usuario_id' => auth()->id(),
        ]);
    
        $codigoBarras->save();
    }    

    public function descargarCodigo()
{
    $id = $this->obtenerUltimoCodigoGenerado();
    if ($this->codigoGenerado) {

        $codigoBarras = CodigoBarras::find($id);
        $fechaGeneracion = $codigoBarras->created_at->toDateTimeString();
        // Crear una nueva imagen con un tamaño personalizado
        $width = 800;
        $height = 400;
        $combinedImg = imagecreatetruecolor($width, $height);

        // Crear colores
        $colorFondo = imagecolorallocate($combinedImg, 255, 255, 255);
        $colorTexto = imagecolorallocate($combinedImg, 0, 0, 0);

        // Rellenar el fondo con blanco
        imagefilledrectangle($combinedImg, 0, 0, $width, $height, $colorFondo);

        // Obtener la imagen del código de barras
        $barcode = imagecreatefrompng($this->codigoGenerado);

        // Obtener el ancho y alto del código de barras
        $anchoCodigo = imagesx($barcode);
        $altoCodigo = imagesy($barcode);

        // Calcular la posición para centrar el código de barras en la imagen
        $xCodigo = ($width - $anchoCodigo) / 2;
        $yCodigo = ($height - $altoCodigo) / 2;

        // Agregar el código de barras a la imagen
        imagecopyresampled($combinedImg, $barcode, $xCodigo, $yCodigo, 0, 0, $anchoCodigo, $altoCodigo, $anchoCodigo, $altoCodigo);

        $xFecha = 20; // Posición personalizada
        $yFecha = $height - 40;

        imagestring($combinedImg,20, $xFecha, $yFecha, $fechaGeneracion, $colorTexto);

        // Generar la imagen
        ob_start();
        imagepng($combinedImg);
        $imagenCombinada = 'data:image/png;base64,' . base64_encode(ob_get_clean());
        imagedestroy($combinedImg);

        // Descargar la imagen
        return response()->stream(
            function () use ($imagenCombinada) {
                echo base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenCombinada));
            },
            200,
            [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename=codigo_barras.png',
            ]
        );
    }
}

public function obtenerUltimoCodigoGenerado()
{
    // Obten el ID del último código generado por el usuario autenticado
    $ultimoCodigo = CodigoBarras::where('usuario_id', Auth::id())
        ->latest('created_at') // Ordena los registros por la columna 'created_at' en orden descendente
        ->first(); // Obtiene el primer registro después de aplicar el ordenamiento

    if ($ultimoCodigo) {
        $idUltimoCodigo = $ultimoCodigo->id;
        // Ahora tienes el ID del último código generado por el usuario autenticado
        return $idUltimoCodigo;
    } else {
        // No se encontraron códigos generados por el usuario
        return null;
    }
}


    public function getCodigosGenerados()
{
    $codigosGenerados = CodigoBarras::where('usuario_id', auth()->id())
        ->latest('created_at')
        ->paginate(12); // Esto paginará los resultados en grupos de 15
    
    return view('components.welcome', compact('codigosGenerados'));
}
    public function render()
    {
        $codigosGenerados = $this->getCodigosGenerados();
        return view('livewire.codigo-barras-generator');
    }
}