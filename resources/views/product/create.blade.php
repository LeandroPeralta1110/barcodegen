<x-app-layout>
<div>
    <section class="container mx-auto p-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                @includeif('partials.errors')

                <div class="bg-white shadow-lg rounded-lg">
                    <div class="p-4 border-b">
                        <span class="text-xl font-bold">{{ __('Create Product') }}</span>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('products.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('product.form')

                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Create') }}
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
