<x-app-layout>
    <div class="container mx-auto">
        <div class="p-6">
            <h2 class="text-2xl font-semibold">{{ __('Update') }}</h2>
        </div>

        @includeif('partials.errors')

        <div class="bg-white shadow-md rounded my-6">
            <div class="p-6">
                <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    @include('user.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>