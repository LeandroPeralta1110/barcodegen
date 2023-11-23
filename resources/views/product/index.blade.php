<x-app-layout>
<div class="mx-auto p-4">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold">{{ __('Products') }}</span>
                <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create New') }}
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-200 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="p-4">
            <div class="table-responsive">
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-100 border-b text-gray-600 text-left text-sm uppercase font-semibold">{{ __('id') }}</th>
                            <th class="px-6 py-3 bg-gray-100 border-b text-gray-600 text-left text-sm uppercase font-semibold">{{ __('Nombre') }}</th>
                            <th class="px-6 py-3 bg-gray-100 border-b text-gray-600 text-left text-sm uppercase font-semibold">{{ __('Descripcion') }}</th>
                            <th class="px-6 py-3 bg-gray-100 border-b"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ++$i }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->descripcion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">Editar</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
