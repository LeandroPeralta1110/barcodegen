<div class="min-h-screen flex justify-center items-center bg-gray-100 bg-cover bg-center bg-fixed imagenfondo">
    <div class="w-1/2 p-4 bg-white shadow-md rounded-md">
        <h2>Códigos Generados por el Usuario</h2>

        <input wire:model="busqueda" type="text" wire:click="getCodigosGenerados" placeholder="Buscar código">

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300 bg-white">
                <thead>
                    <tr>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Código</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Fecha de Generación</th>
                        @role('administrador')
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Usuario</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Producto Vinculado</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @role('usuario')
                    @foreach ($codigosGenerados as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                    @endrole
                    @role('administrador')
                    @foreach ($codigosGeneradosAdmin as $codigoGenerado)
                    <tr>
                        <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                        <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->name }}</td>
                        <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->nombre }}</td>
                    </tr>
                    @endforeach
                    @endrole
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $codigosGenerados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
        </div>
    </div>
    @role('administrador')
    <!-- Gráfico de Torta -->
    <div class="mt-8 ml-8"> <!-- Ajusta el margen izquierdo aquí -->
        <p>Total de Códigos de Barras: {{ $totalCodigos }}</p>
        <h2>Total de Códigos Generados por Producto</h2>
        <canvas id="graficoTorta"></canvas>
    </div>
    @endrole
</div>
   
    <script>
        // Configuración del gráfico de torta
        var ctx = document.getElementById('graficoTorta').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($productosConCodigos->pluck('nombre')),
                datasets: [{
                    data: @json($productosConCodigos->pluck('codigos_barras_count')),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                    ],
                }],
            },
        });
    </script>
