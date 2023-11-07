<x-app-layout>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <div class="w-1/2 p-4">
            <h2>Códigos Generados por el Usuario</h2>

            <input wire:model="busqueda" type="text" class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Buscar código de barras">
            
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 py-2 px-4 bg-gray-100">Código</th>
                            <th class="border border-gray-300 py-2 px-4 bg-gray-100">Fecha de Generación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($codigosGenerados as $codigoGenerado)
                            <tr>
                                <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                                <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>

            <div class="mt-4">
                {{ $codigosGenerados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
        </div>
    </div>
</x-app-layout>
