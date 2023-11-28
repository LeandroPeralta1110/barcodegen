<x-app-layout>
    <div class="container mx-auto p-4">
        @includeif('partials.errors')

        <div class="bg-white shadow-lg rounded-lg mx-auto max-w-md p-4">
            <div class="border-b pb-4">
                <span class="text-xl font-bold">{{ __('Crear Usuario') }}</span>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
                    @csrf
                    @include('user.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
