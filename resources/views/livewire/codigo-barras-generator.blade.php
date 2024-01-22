<div>
    <div class="w-full flex space-x-4">
        <div class="w-1/2 flex flex-col bg-white border border-gray-300 p-8 rounded-md">                       
            <div class="mb-4">
                <label for="" class="control-label mt-2 mb-3 w-full text-left"><b>PRODUCTO</b></label>
                <div class="w-full mt-2">
                    <select wire:model="selectedProduct" class="border rounded-md py-2 px-3 w-2/4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Selecciona un producto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @error('selectedProduct')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
            
            <div class="mb-4">
                <label for="" class="control-label mt-2 w-full text-left"><b>TIPO DE CODIGO DE BARRAS</b></label>
                <div class="w-full mt-2">
                    <select class="border rounded-md py-2 px-8 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model="tipoCodigoBarras">
                        <option value="C128">Code 128</option>
                        {{-- <option value="C128A">Code 128 A</option> --}}
                        <option value="C128B">Code 128 B</option>
                        {{-- <option value="C39">Code 39</option> --}}
                        <option value="C39E">Code 39 E</option>
                        <option value="C93">Code 93</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="cantidadCodigos" class="control-label mt-2 w-full text-left"><b>CANTIDAD DE CODIGOS DE BARRAS A GENERAR</b></label>
                <div class="w-full mt-2">
                    <input wire:model="cantidadCodigos" type="number" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingrese cantidad" required>
                </div>
            </div>

            @if($mostrarPopup)
            <div class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-md shadow-md">
                    <p>¿Quieres generar un nuevo producto para el código escaneado?</p>
                    @if($mostrarFormularioNuevoProducto)
                        <!-- Select para elegir un producto existente -->
                        <div class="mb-4">
                            <label for="existingProduct">Selecciona un Producto Existente</label>
                            <select wire:model="selectedProduct" id="existingProduct" class="border rounded-md py-2 px-3 w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Selecciona un producto</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @role('usuario')
                        <button wire:click="guardarNuevoProducto" class="border border-gray-300 rounded p-2 bg-green-500 text-white mb-2">Generar</button>
                        <button wire:click="ocultarPopup" class="border border-gray-300 rounded p-2 bg-red-500 text-white mb-2">Cancelar</button>
                        @endrole
                        @role('administrador')
                        <!-- Input para el nombre del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoNombre">Nombre del Producto</label>
                            <input wire:model="nuevoProductoNombre" id="nuevoProductoNombre" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
        
                        <!-- Input para la descripción del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoDescripcion">Descripción del Producto</label>
                            <input wire:model="nuevoProductoDescripcion" id="nuevoProductoDescripcion" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value='{{ $alfanumerico }}'>
                        </div>
        
                        <!-- Botones del popup -->
                        <button wire:click="guardarNuevoProducto" class="border border-gray-300 rounded p-2 bg-green-500 text-white mb-2">Generar</button>
                        <button wire:click="ocultarPopup" class="border border-gray-300 rounded p-2 bg-red-500 text-white mb-2">Cancelar</button>
                        @endrole
                        @role('administrador_jumillano')
                        <!-- Input para el nombre del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoNombre">Nombre del Producto</label>
                            <input wire:model="nuevoProductoNombre" id="nuevoProductoNombre" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
        
                        <!-- Input para la descripción del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoDescripcion">Descripción del Producto</label>
                            <input wire:model="nuevoProductoDescripcion" id="nuevoProductoDescripcion" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value='{{ $alfanumerico }}'>
                        </div>
        
                        <!-- Botones del popup -->
                        <button wire:click="guardarNuevoProducto" class="border border-gray-300 rounded p-2 bg-green-500 text-white mb-2">Generar</button>
                        <button wire:click="ocultarPopup" class="border border-gray-300 rounded p-2 bg-red-500 text-white mb-2">Cancelar</button>
                        @endrole
                        @role('administrador_impacto')
                        <!-- Input para el nombre del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoNombre">Nombre del Producto</label>
                            <input wire:model="nuevoProductoNombre" id="nuevoProductoNombre" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
        
                        <!-- Input para la descripción del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoDescripcion">Descripción del Producto</label>
                            <input wire:model="nuevoProductoDescripcion" id="nuevoProductoDescripcion" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value='{{ $alfanumerico }}'>
                        </div>
        
                        <!-- Botones del popup -->
                        <button wire:click="guardarNuevoProducto" class="border border-gray-300 rounded p-2 bg-green-500 text-white mb-2">Generar</button>
                        <button wire:click="ocultarPopup" class="border border-gray-300 rounded p-2 bg-red-500 text-white mb-2">Cancelar</button>
                        @endrole
                        @role('administrador_lavazza')
                        <!-- Input para el nombre del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoNombre">Nombre del Producto</label>
                            <input wire:model="nuevoProductoNombre" id="nuevoProductoNombre" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
        
                        <!-- Input para la descripción del producto -->
                        <div class="mb-4">
                            <label for="nuevoProductoDescripcion">Descripción del Producto</label>
                            <input wire:model="nuevoProductoDescripcion" id="nuevoProductoDescripcion" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value='{{ $alfanumerico }}'>
                        </div>
        
                        <!-- Botones del popup -->
                        <button wire:click="guardarNuevoProducto" class="border border-gray-300 rounded p-2 bg-green-500 text-white mb-2">Generar</button>
                        <button wire:click="ocultarPopup" class="border border-gray-300 rounded p-2 bg-red-500 text-white mb-2">Cancelar</button>
                        @endrole
                        @endif
                </div>
            </div>
        @endif        

        <div class="mb-4 mt-5 mx-auto text-center">
            <!-- Botón centrado -->
            <button wire:click="generarCodigos" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline-green active:bg-green-800 transition-all duration-300 ease-in-out">
                GENERAR CODIGOS DE BARRAS
            </button>
            <span wire:loading wire:target="generarCodigos"><span class="cargando-icono"></span></span>
            <!-- Primer input centrado -->
            <div class="w-full mt-5 relative">
                <label for="scannedCodeInput" class="control-label mt-2 text-left"></label>
                <div class="border border-gray-300 rounded p-2">
                    <!-- Usa el evento @input para capturar los cambios en el campo de entrada -->
                    <input
                        wire:model="scannedCode"
                        id="scannedCodeInput" 
                        name="scannedCodeInput" 
                        placeholder="Escanear código de barras aquí"
                        class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        wire:keydown.enter="enviarCodigoEscaneado"
                    >
                </div>
            
                @if($this->productoCreado)
                        <div class="bg-white p-4 rounded-md shadow-md">
                            <button wire:click="desvincularProducto" class="border border-gray-300 rounded p-2 bg-yellow-500 text-white">Desvincular Producto</button>
                        </div>
                @endif
            </div>
            
            @role('administrador')
            <!-- Segundo input centrado -->
            <div class="w-full mt-5">
                <label for="scannedCodeInputManual" class="control-label mt-2 text-left"></label>
                <div class="border border-gray-300 rounded p-2">
                    <!-- Usa el evento @input para capturar los cambios en el campo de entrada -->
                    <input
                        wire:model="scannedCodeManual"
                        id="scannedCodeInputManual" 
                        name="scannedCodeInputManual" 
                        placeholder="Ingresar manualmente"
                        class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        wire:keydown.enter="enviarCodigoEscaneado"
                    >
                    <button wire:click="generarCodigoManual" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2">Generar Manualmente</button>
                </div>
            </div>  
            <div class="mb-4 mt-5">
                <label for="buscarCodigo" class="control-label mt-2 w-full text-left"><b>Buscar por Código de Barras</b></label>
                <div class="w-full mt-2">
                    <input wire:model="buscarCodigo" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingrese código de barras">
                </div>
            
                @if (!empty($codigosEncontrados))
                    <p class="text-green-500 mt-2"><b>Códigos de Barras Encontrados:</b></p>
                    <ul>
                        @foreach ($codigosEncontrados as $codigoEncontrado)
                            <li>
                                {{ $codigoEncontrado->codigo_barras }} <button wire:click="actualizarEstado({{ $codigoEncontrado->id }})" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2" style="cursor: pointer">Reimprimir</button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-red-500 mt-2"><b>No se encontraron códigos de barras.</b></p>
                @endif
            </div>                
            @endrole 
            @role('administrador_jumillano')
            <!-- Segundo input centrado -->
            <div class="w-full mt-5">
                <label for="scannedCodeInputManual" class="control-label mt-2 text-left"></label>
                <div class="border border-gray-300 rounded p-2">
                    <!-- Usa el evento @input para capturar los cambios en el campo de entrada -->
                    <input
                        wire:model="scannedCodeManual"
                        id="scannedCodeInputManual" 
                        name="scannedCodeInputManual" 
                        placeholder="Ingresar manualmente"
                        class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        wire:keydown.enter="enviarCodigoEscaneado"
                    >
                    <button wire:click="generarCodigoManual" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2">Generar Manualmente</button>
                </div>
            </div>  
            <div class="mb-4 mt-5">
                <label for="buscarCodigo" class="control-label mt-2 w-full text-left"><b>Buscar por Código de Barras</b></label>
                <div class="w-full mt-2">
                    <input wire:model="buscarCodigo" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingrese código de barras">
                </div>
            
                @if (!empty($codigosEncontrados))
                    <p class="text-green-500 mt-2"><b>Códigos de Barras Encontrados:</b></p>
                    <ul>
                        @foreach ($codigosEncontrados as $codigoEncontrado)
                            <li>
                                {{ $codigoEncontrado->codigo_barras }} <button wire:click="actualizarEstado({{ $codigoEncontrado->id }})" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2" style="cursor: pointer">Reimprimir</button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-red-500 mt-2"><b>No se encontraron códigos de barras.</b></p>
                @endif
            </div>                
            @endrole 
            @role('administrador_lavazza')
            <!-- Segundo input centrado -->
            <div class="w-full mt-5">
                <label for="scannedCodeInputManual" class="control-label mt-2 text-left"></label>
                <div class="border border-gray-300 rounded p-2">
                    <!-- Usa el evento @input para capturar los cambios en el campo de entrada -->
                    <input
                        wire:model="scannedCodeManual"
                        id="scannedCodeInputManual" 
                        name="scannedCodeInputManual" 
                        placeholder="Ingresar manualmente"
                        class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        wire:keydown.enter="enviarCodigoEscaneado"
                    >
                    <button wire:click="generarCodigoManual" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2">Generar Manualmente</button>
                </div>
            </div>  
            <div class="mb-4 mt-5">
                <label for="buscarCodigo" class="control-label mt-2 w-full text-left"><b>Buscar por Código de Barras</b></label>
                <div class="w-full mt-2">
                    <input wire:model="buscarCodigo" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingrese código de barras">
                </div>
            
                @if (!empty($codigosEncontrados))
                    <p class="text-green-500 mt-2"><b>Códigos de Barras Encontrados:</b></p>
                    <ul>
                        @foreach ($codigosEncontrados as $codigoEncontrado)
                            <li>
                                {{ $codigoEncontrado->codigo_barras }} <button wire:click="actualizarEstado({{ $codigoEncontrado->id }})" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2" style="cursor: pointer">Reimprimir</button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-red-500 mt-2"><b>No se encontraron códigos de barras.</b></p>
                @endif
            </div>                
            @endrole 
            @role('administrador_impacto')
            <!-- Segundo input centrado -->
            <div class="w-full mt-5">
                <label for="scannedCodeInputManual" class="control-label mt-2 text-left"></label>
                <div class="border border-gray-300 rounded p-2">
                    <!-- Usa el evento @input para capturar los cambios en el campo de entrada -->
                    <input
                        wire:model="scannedCodeManual"
                        id="scannedCodeInputManual" 
                        name="scannedCodeInputManual" 
                        placeholder="Ingresar manualmente"
                        class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        wire:keydown.enter="enviarCodigoEscaneado"
                    >
                    <button wire:click="generarCodigoManual" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2">Generar Manualmente</button>
                </div>
            </div>  
            <div class="mb-4 mt-5">
                <label for="buscarCodigo" class="control-label mt-2 w-full text-left"><b>Buscar por Código de Barras</b></label>
                <div class="w-full mt-2">
                    <input wire:model="buscarCodigo" type="text" class="border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingrese código de barras">
                </div>
            
                @if (!empty($codigosEncontrados))
                    <p class="text-green-500 mt-2"><b>Códigos de Barras Encontrados:</b></p>
                    <ul>
                        @foreach ($codigosEncontrados as $codigoEncontrado)
                            <li>
                                {{ $codigoEncontrado->codigo_barras }} <button wire:click="actualizarEstado({{ $codigoEncontrado->id }})" class="border border-gray-300 rounded p-2 mt-4 bg-blue-500 text-white mb-2" style="cursor: pointer">Reimprimir</button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-red-500 mt-2"><b>No se encontraron códigos de barras.</b></p>
                @endif
            </div>                
            @endrole 
        </div>                  
        </div>                    
        <div class="w-1/2 ml-4">
            <div class="bg-white border border-gray-300 p-4 rounded-md">
                <div id="display" class="flex flex-col items-center">
                    @if($imagenesGeneradas)
                    @if ($codigo && !$codigo->impresion)
                    <button wire:click="marcarTodosComoImpreso" wire:loading.attr="disabled" class="mt-2 bg-green-500 text-white py-1 px-2 rounded">Marcar Todos como Impresos</button>
                    @else
                    <span class="mt-2 bg-gray-300 text-gray-600 py-1 px-2 rounded">Impreso</span>
                    @endif
                        <div id="field" style="width: auto;">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($imagenesGeneradas as $imagenGenerada)
                                <div class="p-2">
                                    <img src="{{ $imagenGenerada }}" alt="Código de barras" style="width: 100%; height: auto;" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                </div>
            </div>
        </div>      
        @if ($mostrarMensaje)
    <div id="mensajePopup" class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center">
        <div class="bg-white border border-gray-300 p-4 rounded-md shadow-lg">
            <p class="text-red-500 font-semibold mb-2">¡El código de barras ya está registrado!</p>
        </div>
    </div>
    <script>
        // Agregamos un id al popup para identificarlo fácilmente
        const mensajePopup = document.getElementById('mensajePopup');

        // Utilizamos setTimeout para ocultar el popup después de 2000 milisegundos (2 segundos)
        setTimeout(() => {
            @this.call('ocultarMensaje');
            // Ocultamos el popup al finalizar el tiempo
            mensajePopup.style.display = 'none';
        }, 1500);
    </script>
    @endif
</div>
</div>
<script>
   window.imagenesGeneradas = @json($imagenesGeneradas);

    let inputBuffer = '';
    let enterPressed = false;
    let isManualEntry = false;

const scannedCodeInput = document.getElementById('scannedCodeInput');

scannedCodeInput.addEventListener('input', function (event) {
    isManualEntry = true;

    // Si se presionó Enter después de un escaneo, consideramos que es una entrada escaneada
    setTimeout(function () {
        if (!enterPressed) {
            Livewire.emit('actualizarEntradaManual', isManualEntry);
        }
        inputBuffer = '';
        isManualEntry = false; // Reiniciamos para la próxima entrada
        enterPressed = false;
        scannedCodeInput.value = ''; // Esto limpiará el valor del input
    }, 100);
    inputBuffer = event.target.value;
});

scannedCodeInput.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        enterPressed = true;
    } else {
        isManualEntry = false;
    }
});

    /* Livewire.on('marcarComoImpreso', codigoBarrasId => {
        @this.marcarComoImpreso(codigoBarrasId);
    });
 */
    /* // Almacena el código acumulado
    Livewire.emit('codigoEscaneado', (scannedCode) => {
        @this.call('enviarCodigoEscaneado', scannedCode);
    }); */
</script>