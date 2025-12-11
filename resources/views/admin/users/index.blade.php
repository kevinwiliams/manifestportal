<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Users</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto p-6">
        <div class="mb-4">
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Create User</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="text-left">
                    <th class="px-2 py-1">ID</th>
                    <th class="px-2 py-1">Name</th>
                    <th class="px-2 py-1">Email</th>
                    <th class="px-2 py-1">Admin</th>
                    <th class="px-2 py-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t">
                        <td class="px-2 py-2">{{ $user->id }}</td>
                        <td class="px-2 py-2">{{ $user->name }}</td>
                        <td class="px-2 py-2">{{ $user->email }}</td>
                        <td class="px-2 py-2">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                        <td class="px-2 py-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600">Edit</a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Delete user?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
