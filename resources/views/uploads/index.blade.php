<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Upload History
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">

        <div class="flex justify-end mb-4">
            <a href="{{ route('uploads.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + New Upload
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pub Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Codes</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Rows</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded By</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                @foreach ($uploads as $upload)
                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 text-sm">
                            <span class="font-semibold">{{ date('Y-m-d', strtotime($upload->pub_date)) }}</span>
                        </td>

                        <td class="px-4 py-3 text-sm">
                            <span>{{ $upload->pub_codes }}</span>

                            @if($upload->pub_code_count >= 2)
                                <span class="ml-2 px-2 py-1 bg-green-200 text-green-700 text-xs rounded">Complete</span>
                            @else
                                <span class="ml-2 px-2 py-1 bg-yellow-200 text-yellow-700 text-xs rounded">Partial</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-sm">{{ $upload->total_rows }}</td>

                        <td class="px-4 py-3 text-sm">{{ $upload->user->name }}</td>

                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('uploads.show', $upload->id) }}"
                               class="text-blue-600 hover:underline">
                               View
                            </a>
                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>

            <div class="p-4 border-t">
                {{ $uploads->links() }}
            </div>
        </div>

    </div>

</x-app-layout>
