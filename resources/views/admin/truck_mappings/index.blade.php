<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Truck Code Mappings
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">

        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.truck-mappings.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Add Mapping
            </a>
        </div>

        <div class="bg-white shadow rounded p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase">Original</th>
                        <th class="px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase">Mapped To</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach ($mappings as $map)
                    <tr>
                        <td class="px-3 py-2">{{ $map->original }}</td>
                        <td class="px-3 py-2">{{ $map->mapped_to }}</td>
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('admin.truck-mappings.edit', $map->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $mappings->links() }}
            </div>

        </div>

    </div>

</x-app-layout>
