<x-app-layout>
    <div class="container mx-auto">
        <div class="p-6">
            <h2 class="text-2xl font-semibold">{{ __('User') }}</h2>
            <div class="text-right">
                <a href="{{ route('users.create') }}" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-700">
                    {{ __('Create New') }}
                </a>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="border px-4 py-2">{{ ++$i }}</td>
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('users.show', $user->id) }}" class="px-2 py-1 text-blue-500 hover:underline">{{ __('Show') }}</a>
                                <a href="{{ route('users.edit', $user->id) }}" class="px-2 py-1 text-green-500 hover:underline">{{ __('Edit') }}</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-red-500 hover:underline">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $users->links() !!}
    </div>
</x-app-layout>