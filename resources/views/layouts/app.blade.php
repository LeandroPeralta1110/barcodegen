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

        <div class="min-h-screen bg-gray-100">
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
    Livewire.on('codigos-generados', function (imagenesGeneradas) {
        const imprimirCódigos = async (imagenes) => {
            // Crear una página HTML con una tabla que contiene las imágenes
            const vistaPrevia = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Vista Previa de Impresión</title>
                </head>
                <body style="margin: 0; padding: 0;">
                    <table style="width: 210mm; height: 297mm; border-collapse: collapse;">
                        ${imagenes.map(imagen => `
                            <tr>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle;">
                                    <img src="${imagen}" style="max-width: 100%; max-height: 100%;" />
                                </td>
                            </tr>
                        `).join('')}
                    </table>
                </body>
                </html>
            `;

            // Abrir una nueva ventana con la vista previa de impresión
            const ventanaImpresion = window.open('', '', 'width=800,height=1000');
            ventanaImpresion.document.open();
            ventanaImpresion.document.write(vistaPrevia);
            ventanaImpresion.document.close();

            // Esperar un breve momento para asegurarse de que el contenido se cargue completamente
            await new Promise(resolve => setTimeout(resolve, 1000));

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

        // Llama a la función para imprimir la vista previa de códigos generados
        imprimirCódigos(imagenesGeneradas);
    });
});

        </script>
    </body>
</html>
