<x-app-layout>
    <section class="content container mx-auto">
        <div class="grid grid-cols-1">
            <div class="col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-semibold">{{ __('Editar Producto') }}</h2>

                        @includeif('partials.errors')

                        <div class="mt-4">
                            <form method="POST" action="{{ route('products.update', $product->id) }}" role="form" enctype="multipart/form-data">
                                @method('PATCH')
                                @csrf

                                @include('product.form')

                                <div class="mt-4">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        {{ __('Editar Producto') }}
                                    </button>
                                    <a href="{{ route('products.index') }}" class="px-4 py-2 ml-4 text-gray-700 hover:underline">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
