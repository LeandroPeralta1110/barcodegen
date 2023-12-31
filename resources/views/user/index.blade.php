<x-app-layout>
    <div class="mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold">{{ __('Usuarios') }}</h2>
                <div class="text-right">
                    <a href="{{ route('users.create') }}" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-700">
                        {{ __('Crear Nuevo') }}
                    </a>
                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="p-4">
                <div class="table-responsive">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('ID') }}</th>
                                <th class="px-4 py-2">{{ __('Nombre') }}</th>
                                <th class="px-4 py-2">{{ __('Email') }}</th>
                                <th class="px-4 py-2">{{__('Unidad de negocio')}}</th>
                                <th class="px-4 py-2">{{ __('Roles') }}</th>
                                <th class="px-4 py-2">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @if ($user->activo)
                                    <tr>
                                        <td class="border px-4 py-2">{{ ++$i }}</td>
                                        <td class="border px-4 py-2">{{ $user->name }}</td>
                                        <td class="border px-4 py-2">{{ $user->email }}</td>
                                        <td class="border px-4 py-2">{{ $user->sucursal->nombre }}</td>
                                        <td class="border px-4 py-2">
                                            @foreach ($user->roles as $role)
                                                {{ $role->name }}<br>
                                            @endforeach
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('users.show', $user->id) }}" class="px-2 py-1 text-blue-500 hover:underline">{{ __('Ver') }}</a>
                                            <a href="{{ route('users.edit', $user->id) }}" class="px-2 py-1 text-green-500 hover:underline">{{ __('Editar') }}</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 text-red-500 hover:underline">{{ __('Eliminar') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        {{ $users->links() }}
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
