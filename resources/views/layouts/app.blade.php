<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100  bg-cover bg-center bg-fixed imagenfondo">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
           function downloadBarcode() {
            var element = document.querySelector('.codigo-barras-container');
            html2canvas(element).then(function(canvas) {
                var link = document.createElement('a');
                link.download = 'codigo_barras.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }

        document.addEventListener('livewire:load', function () {
    let ventanaImpresion = null;

    Livewire.on('codigos-generados', function (imagenesGeneradas) {
        const imprimirCódigos = async (imagenes) => {
            const etiquetaWidth = 112; // Ancho de la etiqueta en milímetros
            const etiquetaHeight = 50; // Longitud máxima de la etiqueta en milímetros
            const dpi = 203; // Puntos por pulgada
            const mmPerInch = 25.4; // Milímetros por pulgada

            // Abre la ventana de impresión
            ventanaImpresion = window.open('', '', `width=${etiquetaWidth * dpi / mmPerInch},height=${etiquetaHeight * dpi / mmPerInch}`);
            ventanaImpresion.document.open();

            // Muestra cada imagen en la ventana de impresión
            for (const imagen of imagenes) {
                const vistaPrevia = `
                    <!DOCTYPE html>
                    <html>
                    <body style="margin: 0; padding: 0; width: ${etiquetaWidth}mm; height: ${etiquetaHeight}mm; page-break-before: always;">
                        <img src="${imagen}" style="width: auto; height: ${etiquetaHeight}mm;" />
                    </body>
                    </html>
                `;

                ventanaImpresion.document.write(vistaPrevia);

                // Espera un breve momento antes de pasar a la siguiente imagen
                await new Promise(resolve => setTimeout(resolve, 1000));
            }

            ventanaImpresion.document.close();

            // Imprime y cierra la ventana después de cargar todas las imágenes
            try {
                ventanaImpresion.print();
                ventanaImpresion.onafterprint = function () {
                    ventanaImpresion.close();
                };
            } catch (error) {
                console.error('Error al imprimir la vista previa:', error);
                ventanaImpresion.close();
            }
        }

        imprimirCódigos(imagenesGeneradas);
    });
});
        </script>
    </body>
</html>
