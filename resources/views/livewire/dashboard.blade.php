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
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Producto Vinculado</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Usuario</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Unidad de Negocio</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Impresiones</th>
                        @endrole
                        @role('administrador_jumillano')
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Producto Vinculado</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Usuario</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Unidad de Negocio</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Impresiones</th>
                        @endrole
                        @role('administrador_impacto')
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Producto Vinculado</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Usuario</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Unidad de Negocio</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Impresiones</th>
                        @endrole
                        @role('administrador_lavazza')
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Producto Vinculado</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Usuario</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Unidad de Negocio</th>
                        <th class="border border-gray-300 py-2 px-4 bg-gray-100">Impresiones</th>
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
                    @foreach ($codigosGeneradosAdministrador as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->name }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->sucursal->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->contador_reimpresiones}}</td>
                        </tr>
                    @endforeach
                    @endrole
                    @role('administrador_lavazza')
                    @foreach ($codigosGeneradosAdminPaginados as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->name }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->sucursal->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->contador_reimpresiones}}</td>
                        </tr>
                    @endforeach
                    @endrole
                    @role('administrador_impacto')
                    @foreach ($codigosGeneradosAdminPaginados as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->name }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->sucursal->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->contador_reimpresiones}}</td>
                        </tr>
                    @endforeach
                    @endrole
                    @role('administrador_jumillano')
                    @foreach ($codigosGeneradosAdminPaginados as $codigoGenerado)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->codigo_barras }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ \Carbon\Carbon::parse($codigoGenerado->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->product->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->name }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->usuario->sucursal->nombre }}</td>
                            <td class="border border-gray-300 py-2 px-4">{{ $codigoGenerado->contador_reimpresiones}}</td>
                        </tr>
                    @endforeach
                    @endrole
                </tbody>
            </table>

            @role('administrador')
            <div class="mt-4">
                {{ $codigosGeneradosAdministrador->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
            @endrole

            @role('administrador_jumillano')
            <div class="mt-4">
                {{ $codigosGeneradosAdminPaginados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
            @endrole

            @role('administrador_lavazza')
            <div class="mt-4">
                {{ $codigosGeneradosAdminPaginados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
            @endrole

            @role('administrador_impacto')
            <div class="mt-4">
                {{ $codigosGeneradosAdminPaginados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
            @endrole

            @role('usuario')
            <div class="mt-4">
                {{ $codigosGeneradosAdminPaginados->links() }} <!-- Muestra los enlaces de paginación debajo de la tabla -->
            </div>
            @endrole
        </div>
    </div>
    @role('administrador')
    <div class="mt-8 ml-8">
        @foreach ($datosTodosProductosAdmin as $sucursal => $datos)
            <!-- Gráfico de Torta para cada Sucursal -->
            <div class="mt-8">
                <p>Total de Códigos de Barras para {{ $sucursal }}: {{ array_sum($datos) }}</p>
                <h2>Total de Códigos Generados por Producto para {{ $sucursal }}</h2>
                @php
                    // Reemplaza espacios y guiones medios con guiones bajos
                    $idSucursal = str_replace([' ', '-'], '_', $sucursal);
    
                    // Configuración para el gráfico
                    $config = [
                        'labels' => array_keys($datos),
                        'data' => array_values($datos),
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                        ],
                    ];
                @endphp
                <canvas id="graficoTorta{{ $idSucursal }}" style="max-width: 300px; max-height: 300px;"></canvas>
            </div>
    
            <script>
                var ctx{{ $idSucursal }} = document.getElementById('graficoTorta{{ $idSucursal }}').getContext('2d');
                var myChart{{ $idSucursal }} = new Chart(ctx{{ $idSucursal }}, {
                    type: 'pie',
                    data: {
                        labels: @json($config['labels']),
                        datasets: [{
                            data: @json($config['data']),
                            backgroundColor: @json($config['backgroundColor']),
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                });
            </script>
        @endforeach
    </div>
    @endrole
    
@role('administrador_jumillano')
<div class="mt-8 ml-8">
    @foreach ($datosTodosProductos as $sucursal => $datos)
        <!-- Gráfico de Torta para cada Sucursal -->
        <div class="mt-8">
            <p>Total de Códigos de Barras para {{ $sucursal }}: {{ array_sum($datos) }}</p>
            <h2>Total de Códigos Generados por Producto para {{ $sucursal }}</h2>
            @php
                // Reemplaza espacios y guiones medios con guiones bajos
                $idSucursal = str_replace([' ', '-'], '_', $sucursal);
            @endphp
            <canvas id="graficoTorta{{ $idSucursal }}" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>

        <script>
            var ctx{{ $idSucursal }} = document.getElementById('graficoTorta{{ $idSucursal }}').getContext('2d');
            var myChart{{ $idSucursal }} = new Chart(ctx{{ $idSucursal }}, {
                type: 'pie',
                data: {
                    labels: @json(array_keys($datos)),
                    datasets: [{
                        data: @json(array_values($datos)),
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
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        </script>
    @endforeach
</div>
@endrole
@role('administrador_lavazza')
<div class="mt-8 ml-8">
    @foreach ($datosTodosProductos as $sucursal => $datos)
        <!-- Gráfico de Torta para cada Sucursal -->
        <div class="mt-8">
            <p>Total de Códigos de Barras para {{ $sucursal }}: {{ array_sum($datos) }}</p>
            <h2>Total de Códigos Generados por Producto para {{ $sucursal }}</h2>
            @php
                // Reemplaza espacios y guiones medios con guiones bajos
                $idSucursal = str_replace([' ', '-'], '_', $sucursal);
            @endphp
            <canvas id="graficoTorta{{ $idSucursal }}" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>

        <script>
            var ctx{{ $idSucursal }} = document.getElementById('graficoTorta{{ $idSucursal }}').getContext('2d');
            var myChart{{ $idSucursal }} = new Chart(ctx{{ $idSucursal }}, {
                type: 'pie',
                data: {
                    labels: @json(array_keys($datos)),
                    datasets: [{
                        data: @json(array_values($datos)),
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
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        </script>
    @endforeach
</div>
@endrole
@role('administrador_impacto')
<div class="mt-8 ml-8">
    @foreach ($datosTodosProductos as $sucursal => $datos)
        <!-- Gráfico de Torta para cada Sucursal -->
        <div class="mt-8">
            <p>Total de Códigos de Barras para {{ $sucursal }}: {{ array_sum($datos) }}</p>
            <h2>Total de Códigos Generados por Producto para {{ $sucursal }}</h2>
            @php
                // Reemplaza espacios y guiones medios con guiones bajos
                $idSucursal = str_replace([' ', '-'], '_', $sucursal);
            @endphp
            <canvas id="graficoTorta{{ $idSucursal }}" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>

        <script>
            var ctx{{ $idSucursal }} = document.getElementById('graficoTorta{{ $idSucursal }}').getContext('2d');
            var myChart{{ $idSucursal }} = new Chart(ctx{{ $idSucursal }}, {
                type: 'pie',
                data: {
                    labels: @json(array_keys($datos)),
                    datasets: [{
                        data: @json(array_values($datos)),
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
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        </script>
    @endforeach
</div>
@endrole
