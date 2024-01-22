<x-app-layout>
<div>
    <section class="container mx-auto p-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                @includeif('partials.errors')

                <div class="bg-white shadow-lg rounded-lg">
                    <div class="p-4 border-b">
                        <span class="text-xl font-bold">{{ __('Crear Producto') }}</span>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('products.store') }}" role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                            @include('product.form')
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Crear') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</x-app-layout>
