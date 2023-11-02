<div>
    <x-app-layout>
    <div class="w-full max-w-md mx-auto p-6 bg-white rounded shadow-md">
        <form wire:submit.prevent="crearProducto" method="post">
            @csrf
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" wire:model="nombre" class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Escribe el nombre del producto" required>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" wire:model="descripcion" class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Agrega una descripción" required>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear Producto
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
</div>