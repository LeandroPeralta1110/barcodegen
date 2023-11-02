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
                <label for="" class="control-label mt-2">Tipo de Código de Barras</label>
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
                <label for="code" class="block"></label>
                <button wire:click="generarCodigo" id="code" class="border border-gray-300 rounded p-2" placeholder="Código">>Generar Código de Barras</button>
            </div>
        </div>

        <div class="w-1/2 ml-4"> <!-- Margen agregado aquí para acercar los contenedores -->
            <div class="bg-white border border-gray-300 p-4 mt-1"> <!-- Ajustado el margen superior aquí -->
                <div id="display" class="flex flex-col items-center">
                    @if ($codigoGenerado)
                        <div id="field" style="width: auto;">
                            <img src="{{ $codigoGenerado }}" alt="Código de barras" style="width: 100%; height: auto;" />
                        </div>
                        <button wire:click="descargarCodigo" class="bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Descargar Código de Barras</button> <!-- Ajustado el margen superior aquí -->
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
