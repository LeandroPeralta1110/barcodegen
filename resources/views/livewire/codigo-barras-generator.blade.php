<div>
<div class="h-screen flex">
    <div class="w-1/2 p-4">
        <div class="mb-4">
            <label for="code" class="block"></label>
            <button wire:click="generarCodigo" id="code" class="w-full border border-gray-300 rounded p-2" placeholder="Código">>Generar Código de Barras</button>
        </div>
    </div>
    
    <div class="w-1/2">
        <div class="bg-white border border-gray-300 p-4 mt-5">
          <div id="display" class="flex items-center h-full">
            @if ($codigoGenerado)
                <p>Código de Barras: {{ $numeroCodigo }}</p>
                <img src="{{ $codigoGenerado }}" alt="Código de Barras">
                <button wire:click="descargarCodigo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Descargar Código de Barras</button>
            @endif
        </div>
    </div>
    </div>
    <div class="w-1/2 p-4">
        <h2>Códigos Generados por el Usuario</h2>

        @if ($codigosGenerados->count() > 0)
            <table class="border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300">Código</th>
                        <th class="border border-gray-300">Fecha de Generación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($codigosGenerados as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300">{{ $codigoGenerado->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No has generado códigos aún.</p>
        @endif
    </div>
</div>
</div>
