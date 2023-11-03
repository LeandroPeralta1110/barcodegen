<div class="h-screen flex">
    <div class="w-full p-8 flex">
        <div class="w-1/2 flex flex-col items-center">
            <div class="mb-4">  
                <label for="" class="control-label mt-2">Producto</label>
                <select wire:model="selectedProduct" class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Selecciona un producto</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->nombre }}</option>
                    @endforeach
                </select>                
            </div>
            <div class="mb-4">  
                <label for="" class "control-label mt-2">Tipo de Código de Barras</label>
                <select class="browser-default custom-select" wire:model="tipoCodigoBarras">
                    <option value="C128">Code 128</option>
                    <option value="C128A">Code 128 A</option>
                    <option value="C128B">Code 128 B</option>
                    <option value="C39">Code 39</option>
                    <option value="C39E">Code 39 E</option>
                    <option value="C93">Code 93</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="cantidadCodigos" class="control-label mt-2">Cantidad de Códigos de Barras a Generar</label>
                <input wire:model="cantidadCodigos" type="number" class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="code" class="block"></label>
                <button wire:click="generarCodigos" id="code" class="border border-gray-300 rounded p-2" placeholder="Código">Generar Código de Barras</button>
            </div>
        </div>

        <div class="w-1/2 ml-4">
            <div class="bg-white border border-gray-300 p-4 mt-1">
                <div id="display" class="flex flex-col items-center">
                    @if($imagenesGeneradas)
                        <div id="field" style="width: auto;">
                            <div class="image-grid">
                                @foreach ($imagenesGeneradas as $imagenGenerada)
                                    <div class="image-item">
                                        <img src="{{ $imagenGenerada }}" alt="Código de barras" style="width: 100%; height: auto;" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button wire:click="emitirCodigosGenerados" id="emitirCodigos" class="border border-gray-300 rounded p-2">Imprimir Códigos Secuencialmente</button>
                        @endif
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    // Almacena las imágenes generadas en una variable JavaScript
    window.imagenesGeneradas = @json($imagenesGeneradas);
</script>
