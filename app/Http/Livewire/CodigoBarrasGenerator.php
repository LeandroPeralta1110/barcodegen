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

    protected $listeners = ['actualizarEntradaManual' => 'actualizarEntradaManual'];
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
    public $codigoBarrasId;
    public $mostrarPopup = false;
    public $enviarAutomaticamente = true;
    public $esEntradaManual = false;
    public $entradaManual = false;
    public $alfanumerico;
    public $nuevoProductoNombre;
    public $nuevoProductoDescripcion;
    public $mostrarFormularioNuevoProducto = false;
    public $esperandoDecisionUsuario = true;
    public $scannedCodeManual;
    public $productoCreado = false;
    public $producto;
    public $codigosEncontrados = [];
    public $buscarCodigo;
    public $actualizarEstado=false;

    public function updatedBuscarCodigo()
{
    $this->buscarCodigo();
}
    
    public function generarCodigo($scannedCode = null)
{
    if ($this->esEntradaManual) {
        return;
    }

    if ($scannedCode !== null) {
        // Verificar si el producto ya ha sido creado
        if (!$this->productoCreado) {
            // Mostrar el formulario y el popup solo si el producto no ha sido creado
            $this->entradaManual = true;
            $this->mostrarFormularioNuevoProducto = true;
            $this->numeroCodigo = $scannedCode;
            $this->mostrarPopup();
            $this->esperandoDecisionUsuario = true;
            // El usuario ha escaneado un código de barras, usa el valor escaneado
        } else {
            // Vincular automáticamente el código al producto creado
            $this->numeroCodigo = $scannedCode;
            $this->generateBarcodeFromCode($this->numeroCodigo, $this->producto);
            // No es necesario mostrar el formulario y el popup nuevamente
        }
    } else {
        // El usuario no escaneó un código de barras, genera un nuevo código aleatorio
        do {
            $nuevoCodigo = $this->generateUniqueCode();
        } while (CodigoBarras::where('codigo_barras', $nuevoCodigo)->exists());

        $this->numeroCodigo = $nuevoCodigo;
        // Genera el código de barras a partir del código alfanumérico
        $this->generateBarcodeFromCode($this->numeroCodigo, $this->productoCreado);
    }
}

public function desvincularProducto()
{
    // Cambiar el estado de productoCreado a false
    $this->productoCreado = false;
}

public function enviarCodigoEscaneado()
{
    if (CodigoBarras::where('codigo_barras', $this->scannedCode)->exists()) {
        $this->mostrarMensaje = true;
        return;
    }

    // Restablecer la bandera después de procesar el código
    if (!$this->esEntradaManual && $this->esperandoDecisionUsuario) {
        // Procesa el código escaneado automáticamente solo si no es una entrada manual
        $this->generarCodigo($this->scannedCode);
    }
    $this->scannedCode = '';
}

public function guardarNuevoProducto()
{
    // Validar la entrada del usuario si es necesario

    // Crear un nuevo producto
    $nuevoProducto = Product::create([
        'nombre' => $this->nuevoProductoNombre,
        'descripcion' => $this->nuevoProductoDescripcion,
        'sucursal_id' => auth()->user()->sucursal_id,
    ]);

    // Usar el código introducido manualmente en lugar del escaneado
    $this->generateBarcodeFromCode($this->numeroCodigo, $nuevoProducto);

    // Marcar que el producto ha sido creado
    $this->productoCreado = true;
    $this->producto = $nuevoProducto;

    $this->mostrarPopup = false;
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
    if (CodigoBarras::where('codigo_barras', $code)->exists() && !$this->actualizarEstado) {
        $this->mostrarMensaje = true;
        return;
    }

    /* CodigoBarras::where('product_id', $this->selectedProduct)
        ->update(['impresion' => true]); */

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

    if (!empty($nombre)) {
        // Obtener el producto seleccionado
        $product = Product::find($nombre);
    } else {
        $product = Product::find($this->selectedProduct);
    }

    // Crear un color negro para el texto
    $colorTexto = imagecolorallocate($combinedImg, 0, 0, 0);

    // Calcular la posición para el nombre del producto
    $font_size = 20; // Tamaño de fuente

    if (!empty($nombre)) {
        $xNombreProducto = ($anchoCodigo - imagefontwidth($font_size) * strlen($product)) / 2;
    } else {
        $xNombreProducto = ($anchoCodigo - imagefontwidth($font_size) * strlen($product->nombre)) / 2;
    }

    $yNombreProducto = 10; // Espacio desde la parte superior

    if (!empty($nombre)) {
        imagestring($combinedImg, $font_size, $xNombreProducto, $yNombreProducto, $product->first()->nombre, $colorTexto);
    } else {
        // Agregar el nombre del producto como texto en la imagen combinada
        imagestring($combinedImg, $font_size, $xNombreProducto, $yNombreProducto, $product->nombre, $colorTexto);
    }

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

    // Definir la variable $mmPerInch
    $mmPerInch = 25.4;

    // Nuevos valores para el ancho y alto deseados
    $etiquetaWidth = 75; // Ancho de la etiqueta en milímetros
    $etiquetaHeight = 26; // Longitud máxima de la etiqueta en milímetros
    // Definir la variable $dpi
    $dpi = 203; // Puntos por pulgada

    // Calcula el nuevo ancho y alto del código de barras para que quepa en la etiqueta
    $newWidth = ($etiquetaWidth / $mmPerInch) * $dpi;
    $newHeight = ($etiquetaHeight / $mmPerInch) * $dpi;

    // Redimensiona la imagen del código de barras
    $combinedImg = imagescale($combinedImg, $newWidth, $newHeight);

    ob_start();
    imagepng($combinedImg);
    $imgData = ob_get_clean(); // Obtén los datos de la imagen en formato binario
    imagedestroy($combinedImg);
    imagedestroy($img);

    $this->codigoGenerado = 'data:image/png;base64,' . base64_encode($imgData);

    if (!empty($nombre)) {
        $this->imagenesGeneradas[] = $this->codigoGenerado;
    }

    if(!$this->actualizarEstado){
        // Guarda el código y la imagen en la base de datos
        $codigoBarras = new CodigoBarras([
           'codigo_barras' => $code,
           'usuario_id' => auth()->id(),
           'product_id' => (!empty($nombre)) ? $nombre->id : $this->selectedProduct,
           'imagen_codigo_barras' => $this->codigoGenerado,
           'impresion' => false, // Establecer el valor por defecto como false
           'created_at' => now('America/Argentina/Buenos_Aires'),
       ]);
       $codigoBarras->save();
    }

    $this->actualizarEstado=false;
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
    $this->emitirCodigosGenerados(); */
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

public function marcarTodosComoImpreso()
{
    // Obtener la cantidad de códigos que el usuario quiere generar
    $cantidadCodigos = $this->cantidadCodigos;

    // Obtener los últimos códigos generados por el usuario
    $ultimosCodigos = CodigoBarras::where('usuario_id', Auth::id())
        ->latest('created_at')
        ->take($cantidadCodigos)
        ->get();

    // Marcar cada código como impreso
    foreach ($ultimosCodigos as $codigo) {
        if (!$codigo->impresion) {
            $codigo->impresion = true;
            $codigo->save();
        }
    }
}

public function actualizarEntradaManual($valor)
{
    $this->esEntradaManual = $valor;
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
    // Obten el último código generado por el usuario autenticado
    $ultimoCodigo = CodigoBarras::where('usuario_id', Auth::id())
        ->latest('created_at') // Ordena los registros por la columna 'created_at' en orden descendente
        ->first(); // Obtiene el primer registro después de aplicar el ordenamiento

    return $ultimoCodigo;
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
   /*  $this->emit('codigos-generados', $this->imagenesGeneradas); */
    // Marcar como impreso después de emitir los códigos generados
    $this->marcarComoImpresoParaGenerados();
}

public function marcarComoImpresoParaGenerados()
{
    // Obtén los IDs de los códigos de barras generados
    $idsGenerados = CodigoBarras::whereIn('imagen_codigo_barras', $this->imagenesGeneradas)
        ->pluck('id');

    // Marcar los códigos de barras como impresos
    CodigoBarras::whereIn('id', $idsGenerados)
        ->update(['impresion' => true]);
}

public function mostrarPopup()
{
    $this->mostrarPopup = true;
}

public function ocultarPopup()
{
    $this->mostrarPopup = false;
    $this->crearProductoAlfanumerico();
}

private function crearProductoAlfanumerico(){
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

$this->alfanumerico =  $codigoAlfanumerico;

// Obtener el producto seleccionado o crear uno nuevo
$product = Product::firstOrCreate([
    'descripcion' => $codigoAlfanumerico,
    'nombre' => $codigoAlfanumerico,
    'sucursal_id' => auth()->user()->sucursal_id,
    ]);

    // Genera el código de barras a partir del código alfanumérico y el producto obtenido
    $this->generateBarcodeFromCode($this->numeroCodigo, $product);
}

public function generarCodigoManual()
{
    $this->generarCodigo($this->scannedCodeManual);
    $this->scannedCodeManual = '';
}

public function buscarCodigo()
{
    // Aquí debes implementar la lógica para buscar el código de barras en la base de datos
    // y asignar el resultado a la propiedad $codigosEncontrados

    // Verifica si el campo de búsqueda está vacío
    if (!empty($this->buscarCodigo)) {
        $query = CodigoBarras::with(['usuario.sucursal', 'product'])
            ->where('codigo_barras', 'like', '%' . $this->buscarCodigo . '%')
            ->latest('created_at')
            ->get();

        $this->codigosEncontrados = $query;
    } else {
        // Si el campo de búsqueda está vacío, no hagas ninguna búsqueda y establece la propiedad como un array vacío
        $this->codigosEncontrados = [];
    }
}

public function actualizarEstado($codigoId)
{
    $codigo = CodigoBarras::find($codigoId);

    if ($codigo) {
        // Incrementa el contador de reimpresiones
        $nuevoContadorReimpresiones = $codigo->contador_reimpresiones + 1;

        // Actualiza el estado y el contador de reimpresiones del código de barras
        $codigo->update([
            'impresion' => 0,
            'contador_reimpresiones' => $nuevoContadorReimpresiones,
        ]);

        $this->actualizarEstado=true;

        // Vuelve a generar la imagen del código de barras actualizado
        $this->generateBarcodeFromCode($codigo->codigo_barras, $codigo->product_id);

        // Vuelve a ejecutar la búsqueda para actualizar la lista de códigos
        $this->buscarCodigo();
    }
}

public function render()
    {
        // Modificar la consulta para obtener solo productos de la sucursal del usuario actual
        $this->products = Product::where('sucursal_id', auth()->user()->sucursal_id)->get();

        $ultimoCodigoGenerado = $this->obtenerUltimoCodigoGenerado(); // Agregado
        return view('livewire.codigo-barras-generator', [
            'imagenesGeneradas' => $this->imagenesGeneradas,
            'codigo' => $ultimoCodigoGenerado, // Agregado
        ]);
    }
}