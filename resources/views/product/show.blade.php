<x-app-layout>
    <section class="content container mx-auto">
        <div class="grid grid-cols-1">
            <div class="col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold">{{ __('Show Product') }}</h2>
                            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                {{ __('Back') }}
                            </a>
                        </div>
                        <div class="mt-4">
                            <div class="mb-4">
                                <strong class="block text-gray-700">Nombre:</strong>
                                <span class="text-gray-900">{{ $product->nombre }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="block text-gray-700">Descripción:</strong>
                                <span class="text-gray-900">{{ $product->descripcion }}</span>
                            </div>
                            <div>
                                <strong class="block text-gray-700">Fecha de Creación:</strong>
                                <span class="text-gray-900">{{ $product->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
