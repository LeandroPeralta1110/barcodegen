<div>
    <div>
        <button wire:click="generarCodigo">Generar Código de Barras</button>
        @if ($codigoGenerado)
            <p>Código de Barras: {{ $numeroCodigo }}</p>
            <img src="{{ $codigoGenerado }}" alt="Código de Barras">
            <button wire:click="descargarCodigo">Descargar Código de Barras</button>
        @endif
    </div>

    <h2>Códigos Generados por el Usuario</h2>

    @if ($codigosGenerados->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha de Generación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($codigosGenerados as $codigoGenerado)
                    <tr>
                        <td>{{ $codigoGenerado->codigo_barras }}</td>
                        <td>{{ $codigoGenerado->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No has generado códigos aún.</p>
    @endif
</div>
