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
use App\Models\Product;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\PrintConnectorInterface;

class CodigoBarrasGenerator extends Component
{
    use WithFileUploads;

    protected $listeners = ['codigoEscaneado' => 'generarCodigo'];
    public $codigosGenerados;
    public $codigoGenerado;
    public $numeroCodigo;
    public $tipoCodigoBarras = 'C128';
    public $selectedProduct;
    public $products;
    public $imagenesGeneradas;
    public $cantidadCodigos;
    public $busqueda='';
    public $generarCodigo;
    public $scannedCode;

    public function generarCodigo($scannedCode = null)
{
    if ($scannedCode !== null) {
        
        // El usuario ha escaneado un código de barras, usa el valor escaneado
        $this->numeroCodigo = $scannedCode;
    } else {
        // El usuario no escaneó un código de barras, genera un nuevo código aleatorio
        do {
            $nuevoCodigo = $this->generateUniqueCode();
        } while (CodigoBarras::where('codigo_barras', $nuevoCodigo)->exists());
    
        $this->numeroCodigo = $nuevoCodigo;
    }

    // Genera el código de barras a partir del código alfanumérico
    $this->generateBarcodeFromCode($this->numeroCodigo);
}

public function enviarCodigoEscaneado($scannedCode)
{
    // Aquí puedes usar $this->scannedCode para acceder al código escaneado
    $this->generarCodigo($scannedCode);
}

private function generateUniqueCode()
{
    $product = Product::find($this->selectedProduct);

    // Genera un número aleatorio de 6 dígitos
    $numeroAleatorio = strval(mt_rand(100000, 999999));

    // Crea un código alfanumérico único concatenando la descripción del producto y el número aleatorio
    $codigoAlfanumerico = $product->descripcion . $numeroAleatorio;

    return $codigoAlfanumerico;
}

private function generateBarcodeFromCode($code)
{
    $generator = new BarcodeGeneratorPNG();
    $imgData = $generator->getBarcode($code, $this->tipoCodigoBarras);
    $img = imagecreatefromstring($imgData);

    // Calcular el ancho y alto de la imagen del código de barras
    $anchoCodigo = imagesx($img);
    $altoCodigo = imagesy($img);

    // Espaciado entre los números y el código de barras
    $espaciadoNumeros = 10;

    // Aumentar el alto de la imagen para dar más espacio al número de código de barras
    $altoDeseado = $altoCodigo + $espaciadoNumeros + 100; // Aumenta el espacio para el número

    // Crear la imagen combinada con el nuevo tamaño
    $combinedImg = imagecreatetruecolor($anchoCodigo, $altoDeseado);

    // Crear un color blanco para el fondo
    $colorFondo = imagecolorallocate($combinedImg, 255, 255, 255);

    // Rellenar el fondo con blanco
    imagefilledrectangle($combinedImg, 0, 0, $anchoCodigo, $altoDeseado, $colorFondo);

    // Obtener el producto seleccionado
    $product = Product::find($this->selectedProduct);

    // Crear un color negro para el texto
    $colorTexto = imagecolorallocate($combinedImg, 0, 0, 0);

    // Calcular la posición para el nombre del producto
    $font_size = 20; // Tamaño de fuente
    $xNombreProducto = ($anchoCodigo - imagefontwidth($font_size) * strlen($product->nombre)) / 2;
    $yNombreProducto = 10; // Espacio desde la parte superior

    // Agregar el nombre del producto como texto en la imagen combinada
    imagestring($combinedImg, $font_size, $xNombreProducto, $yNombreProducto, $product->nombre, $colorTexto);

    // Calcular la posición donde se copiará el código de barras en la imagen (centrado horizontalmente)
    $xCodigo = ($anchoCodigo - $anchoCodigo) / 2;
    $yCodigo = $yNombreProducto + $font_size + $espaciadoNumeros; // Espacio entre el nombre y el código

    // Copiar el código de barras en la imagen combinada
    imagecopy($combinedImg, $img, $xCodigo, $yCodigo, 0, 0, $anchoCodigo, $altoCodigo);

    // Calcular la posición para el número de código de barras
    $xNumero = ($anchoCodigo - imagefontwidth($font_size) * strlen($code)) / 2;
    $yNumero = $yCodigo + $altoCodigo + $espaciadoNumeros; // Espacio entre el código y el número

    // Agregar el número de código de barras como texto en la imagen combinada
    imagestring($combinedImg, $font_size, $xNumero, $yNumero, $code, $colorTexto);

    // Generar la imagen combinada en tiempo real y mostrarla en la vista
    ob_start();
    imagepng($combinedImg);
    $imagenCombinada = 'data:image/png;base64,' . base64_encode(ob_get_clean());
    imagedestroy($combinedImg);

    $this->codigoGenerado = $imagenCombinada;

    // Guarda el código en la base de datos
    $codigoBarras = new CodigoBarras([
        'codigo_barras' => $code,
        'usuario_id' => auth()->id(),
        'product_id' => $this->selectedProduct,
    ]);
    $codigoBarras->save();
}

public function generarCodigos()
{
    $this->imagenesGeneradas = [];

    for ($i = 0; $i < $this->cantidadCodigos; $i++) {
        $this->generarCodigo();
        $this->imagenesGeneradas[] = $this->codigoGenerado;
    }
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

public function imprimirCodigosSecuencia()
{
    // Nombre de la impresora TSC TTP-244 Pro
    $printerName = "TSC TTP-244 Pro";
    
    // Ruta de las imágenes que deseas imprimir en secuencia
    $imagenesAImprimir = $this->imagenesGeneradas;

    try {
        // Conecta con la impresora
        $connector = new WindowsPrintConnector($printerName);

        foreach ($imagenesAImprimir as $imagenAImprimir) {
            // Abre una nueva conexión para cada imagen
            $printer = new Printer($connector);

            // Carga la imagen desde los datos base64
            $escposImage = EscposImage::load($imagenAImprimir);

            // Imprime la imagen actual
            $printer->graphics($escposImage);

            // Corta el papel
            $printer->cut();

            // Cierra la conexión con la impresora
            $printer->close();

            // Espera un tiempo antes de imprimir la siguiente imagen (puedes ajustar el tiempo)
            sleep(2); // Espera 2 segundos, por ejemplo
        }

        session()->flash('success', 'Imágenes impresas en secuencia en TSC TTP-244 Pro');
    } catch (\Exception $e) {
        session()->flash('error', 'Error al imprimir las imágenes: ' . $e->getMessage());
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
    $query = CodigoBarras::where('usuario_id', auth()->id())
        ->latest('created_at');

        if (!empty($this->busqueda)) {
            dd($this->busqueda);
        $query->where('codigo_barras', 'like', '%' . $this->busqueda . '%');
    }
    $codigosGenerados = $query->paginate(12);
    return view('components.welcome', compact('codigosGenerados'));
}

public function emitirCodigosGenerados()
{
    $this->emit('codigos-generados', $this->imagenesGeneradas);
    $this->imprimirCodigosSecuencia();
}

public function render()
{
    $this->products = Product::all();
    return view('livewire.codigo-barras-generator', ['imagenesGeneradas' => $this->imagenesGeneradas]);
}
}