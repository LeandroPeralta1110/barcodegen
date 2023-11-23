<x-app-layout>
    <section class="content container mx-auto">
        <div class="grid grid-cols-1">
            <div class="col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-semibold">{{ __('Update') }}</h2>
                    </div>

                    @includeif('partials.errors')

                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">{{ __('Show User') }}</span>
                            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                {{ __('Back') }}
                            </a>
                        </div>
                        <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            @include('user.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
