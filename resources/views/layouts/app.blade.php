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
        <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.3/qz-tray.min.js"></script>        
        
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
/* 
            document.addEventListener('livewire:load', function () {
        Livewire.on('imprimirDirecto', function (imagenesGeneradas) {
            imprimirConQZTray(imagenesGeneradas);
        });

        Livewire.on('imprimirCodigosSecuencia', function (imagenesGeneradas) {
            imprimirCodigosSecuenciaConQZTray(imagenesGeneradas);
        });
    }); */

    /* function imprimirConQZTray(imagenesGeneradas) {
        // Configuración de la impresora (ajusta según tus necesidades)
        const configuracionImpresora = qz.configs.create(null);
        configuracionImpresora.reconfigure({ copies: 1 });

        // Recorre las imágenes generadas y las imprime una por una
        imagenesGeneradas.forEach(function (imagenGenerada) {
            const datosImpresion = [
                qz.printing.newLine,
                { type: 'image', format: 'base64', data: imagenGenerada, options: { language: 'epl' } },
                qz.printing.newLine,
            ];

            // Imprime con QZ Tray
            qz.printing.print(configuracionImpresora, datosImpresion);
        });
    }

    function imprimirCodigosSecuenciaConQZTray(imagenesGeneradas) {
        // Implementa la lógica para imprimir códigos secuencialmente aquí
        // Puedes utilizar funciones como setTimeout para añadir un retraso entre las impresiones
        // Ejemplo:
        imagenesGeneradas.forEach(function (imagenGenerada, index) {
            setTimeout(function () {
                imprimirConQZTray([imagenGenerada]);
            }, index * 2000); // Imprime cada código después de 2 segundos
        });
    } */

    document.addEventListener('livewire:load', function () {
    let ventanaImpresion = null;

    Livewire.on('codigos-generados', function (imagenesGeneradas) {
        const imprimirCodigos = async (imagenes) => {
            const etiquetaWidth = 70; // Ancho de la etiqueta en milímetros
            const etiquetaHeight = 20; // Longitud máxima de la etiqueta en milímetros

            // Abre la ventana de impresión
            ventanaImpresion = window.open('', '_blank');
            ventanaImpresion.document.open();

            // Define el contenido CSS para asegurar el tamaño correcto en la impresión
            const estiloCSS = `
                <style>
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                            width: ${etiquetaWidth}mm;
                            height: ${etiquetaHeight}mm;
                            page-break-before: always; /* Nueva página para cada etiqueta */
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        img {
                            max-width: 100%;
                            max-height: 100%;
                        }
                        /* Oculta elementos no deseados durante la impresión */
                        .horario, .autoblank {
                            display: none !important;
                        }
                    }
                </style>
            `;

            ventanaImpresion.document.write(estiloCSS);

            // Muestra cada imagen en la ventana de impresión
            for (const imagen of imagenes) {
                const vistaPrevia = `
                    <html>
                        <body>
                            <img src="${imagen}" />
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
        };

        // Imprime los códigos solo si hay imágenes generadas
        if (imagenesGeneradas && imagenesGeneradas.length > 0) {
            imprimirCodigos(imagenesGeneradas);
        }
    });
});
        </script>
    </body>
</html>
