<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Create User</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6">
        @if($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('admin.users._form')
        </form>
    </div>
</x-app-layout>
