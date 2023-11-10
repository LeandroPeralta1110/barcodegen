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

    protected $listeners = ['codigoEscaneado' => 'enviarCodigoEscaneado'];
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
    public $page = 1;
    public $mostrarMensaje = false;


    public function generarCodigo($scannedCode = null)
    {
        if ($scannedCode !== null) {
            // El usuario ha escaneado un código de barras, usa el valor escaneado
            $this->numeroCodigo = $scannedCode;
    
            // Inicializar variables para el código alfanumérico y numérico
            $codigoAlfanumerico = '';
            $numeroCodigoBarras = '';
    
            // Iterar sobre cada carácter en el código
            $caracteres = str_split($this->numeroCodigo);
    
            foreach ($caracteres as $key => $caracterActual) {
                // Verificar si el carácter actual es numérico
                if (is_numeric($caracterActual)) {
                    // Verificar los tres caracteres siguientes
                    $siguientesTres = array_slice($caracteres, $key + 1, 3);
    
                    // Si son todos numéricos, cortar en el carácter actual
                    if (count($siguientesTres) === 3 && ctype_digit(implode('', $siguientesTres))) {
                        break;
                    }
    
                    $codigoAlfanumerico .= $caracterActual;
                } else {
                    $codigoAlfanumerico .= $caracterActual;
                }
            }
    
            // Obtener el producto seleccionado o crear uno nuevo
            $product = Product::firstOrCreate([
                'descripcion' => $codigoAlfanumerico,
                'nombre' => $codigoAlfanumerico,
            ]);
    
            // Genera el código de barras a partir del código alfanumérico y el producto obtenido
            $this->generateBarcodeFromCode($this->numeroCodigo, $product);
        } else {
            // El usuario no escaneó un código de barras, genera un nuevo código aleatorio
            do {
                $nuevoCodigo = $this->generateUniqueCode();
            } while (CodigoBarras::where('codigo_barras', $nuevoCodigo)->exists());
    
            $this->numeroCodigo = $nuevoCodigo;
            // Genera el código de barras a partir del código alfanumérico
            $this->generateBarcodeFromCode($this->numeroCodigo);
        }
    }      
    
    public function enviarCodigoEscaneado($scannedCode)
    {
        // Aquí puedes usar $this->scannedCode para acceder al código escaneado
        $this->generarCodigo($scannedCode);
        $this->scannedCode = '';
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

private function generateBarcodeFromCode($code, $nombre = null)
{
    // Verificar si el código ya está registrado
    if (CodigoBarras::where('codigo_barras', $code)->exists()) {
        $this->mostrarMensaje = true;
        return;
    }

    // Inicializar variables para el código alfanumérico y numérico
    $codigoAlfanumerico = '';
    $numeroCodigoBarras = '';

    // Iterar sobre cada carácter en el código
    for ($i = 0; $i < strlen($code); $i++) {
        $caracterActual = $code[$i];

        // Verificar si el carácter actual es numérico
        if (is_numeric($caracterActual)) {
            // Si es numérico, agrégalo al número del código de barras
            $numeroCodigoBarras .= $caracterActual;
        } else {
            // Si no es numérico, significa que estamos en la parte alfanumérica
            $codigoAlfanumerico .= $caracterActual;
        }
    }

    // Crear el código de barras
    $generator = new BarcodeGeneratorPNG();
    $imgData = $generator->getBarcode($code, $this->tipoCodigoBarras);
    $img = imagecreatefromstring($imgData);

    // Calcular el ancho y alto de la imagen del código de barras
    $anchoCodigo = imagesx($img);
    $altoCodigo = imagesy($img);

    // Espaciado entre los números y el código de barras
    $espaciadoNumeros = 5; // Reducido el espaciado

    // Definir el tamaño deseado de la imagen del código de barras
    $desiredWidth = 800; // Puedes ajustar este valor según tus necesidades
    $desiredHeight = 200; // Reducido el alto de la imagen

    // Crear una nueva imagen con el tamaño deseado
    $combinedImg = imagecreatetruecolor($desiredWidth, $desiredHeight);

    // Crear un color blanco para el fondo
    $colorFondo = imagecolorallocate($combinedImg, 255, 255, 255);

    // Rellenar el fondo con blanco
    imagefilledrectangle($combinedImg, 0, 0, $desiredWidth, $desiredHeight, $colorFondo);

    // Obtener el producto seleccionado
    $product = (!empty($nombre)) ? Product::find($nombre) : Product::find($this->selectedProduct);

    // Crear un color negro para el texto
    $colorTexto = imagecolorallocate($combinedImg, 0, 0, 0);

    // Calcular la posición para el nombre del producto
    $font_size = 20; // Tamaño de fuente

    $xNombreProducto = ($desiredWidth - imagefontwidth($font_size) * strlen($product->nombre)) / 2;
    $yNombreProducto = 5; // Reducido el espacio desde la parte superior

    // Agregar el nombre del producto como texto en la imagen combinada
    imagestring($combinedImg, $font_size, $xNombreProducto, $yNombreProducto, $product->nombre, $colorTexto);

    $xCodigo = ($desiredWidth - $anchoCodigo) / 2;
    $yCodigo = $yNombreProducto + $font_size + $espaciadoNumeros; // Reducido el espacio entre el nombre y el código

    // Rotar el código de barras antes de copiarlo en la imagen combinada
    $imgRotated = imagerotate($img, 0, 0);
    imagecopy($combinedImg, $imgRotated, $xCodigo, $yCodigo, 0, 0, $anchoCodigo, $altoCodigo);

    // Calcular la posición para el número de código de barras
    $xNumero = ($desiredWidth - imagefontwidth($font_size) * strlen($code)) / 2;
    $yNumero = $yCodigo + $altoCodigo + $espaciadoNumeros; // Espacio entre el código y el número

    // Agregar el número de código de barras como texto en la imagen combinada
    imagestring($combinedImg, $font_size, $xNumero, $yNumero, $code, $colorTexto);

    // Genera la imagen combinada en tiempo real y muéstrala en la vista
    ob_start();
    imagepng($combinedImg);
    $imagenCombinada = 'data:image/png;base64,' . base64_encode(ob_get_clean());
    imagedestroy($combinedImg);
    imagedestroy($img);
    imagedestroy($imgRotated);  
    $this->codigoGenerado = $imagenCombinada;

    if (!empty($nombre)) {
        $this->imagenesGeneradas[] = $this->codigoGenerado;
    }

    // Guarda el código en la base de datos
    $codigoBarras = new CodigoBarras([
        'codigo_barras' => $code,
        'usuario_id' => auth()->id(),
        'product_id' => (!empty($nombre)) ? $nombre->id : $this->selectedProduct,
        'created_at' => now('America/Argentina/Buenos_Aires'), // Agrega la zona horaria
    ]);

    $codigoBarras->save();
}

public function ocultarMensaje()
{
    $this->mostrarMensaje = false;
    $this->emit('limpiarInput'); // Puedes emitir un evento si necesitas realizar alguna otra acción
}

public function generarCodigos()
{
    $this->imagenesGeneradas = [];

    for ($i = 0; $i < $this->cantidadCodigos; $i++) {
        // Generar un nuevo código de barras
        $this->generarCodigo();

        // Verificar si se mostró el mensaje de código duplicado y salir del bucle si es necesario
        if ($this->mostrarMensaje) {
            break;
        }

        // Asegúrate de que $this->codigoGenerado no sea nulo antes de agregarlo al array
        if (!is_null($this->codigoGenerado)) {
            $this->imagenesGeneradas[] = $this->codigoGenerado;
        }

        // Limpiar el código generado para la próxima iteración
        $this->codigoGenerado = null;
    }

    /* // Emitir el evento después de haber generado todos los códigos
    $this->emit('codigos-generados', $this->imagenesGeneradas); */
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
    $nombreImpresora = "impresora-termica1";
    $connector = new WindowsPrintConnector($nombreImpresora);
    $impresora = new Printer($connector);
    $impresora->setJustification(Printer::JUSTIFY_CENTER);
    $impresora->setTextSize(2, 2);
    $impresora->text("Imprimiendo\n");
    $impresora->text("ticket\n");
    $impresora->text("desde\n");
    $impresora->text("Laravel\n");
    $impresora->setTextSize(1, 1);
    $impresora->text("https://parzibyte.me");
    $impresora->feed(5);
    $impresora->close();
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

    $codigosGenerados = $query->paginate(12, ['*'], 'page', $this->page);
   
    return view('components.welcome', ['codigosGenerados' => $codigosGenerados]);
}

public function emitirCodigosGenerados()
{
    $this->emit('codigos-generados', $this->imagenesGeneradas);
    $this->imprimirCodigosSecuencia();
}

public function updated($propertyName)
{
    if ($propertyName === 'scannedCode') {
        $this->emit('limpiarInput');
    }
}

public function render()
{
    $this->products = Product::all();
    return view('livewire.codigo-barras-generator', ['imagenesGeneradas' => $this->imagenesGeneradas]);
}
}