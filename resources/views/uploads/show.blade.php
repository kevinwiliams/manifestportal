<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Upload Details – {{ $upload->pub_code }} ({{ $upload->pub_date }})
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        <div class="bg-white shadow rounded p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-600">Publication Date</p>
                    <p class="text-lg font-semibold">{{ $upload->pub_date }}</p>
                </div>

                <div>
                    @if($upload->status === 'pending')
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                            Pending – waiting for second pub code for this date
                        </span>
                    @elseif($upload->status === 'completed')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                            Completed – combined manifest created
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Publication Code</p>
                    <p class="text-gray-800 font-medium">{{ $upload->pub_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Imported Rows</p>
                    <p class="text-gray-800 font-medium">{{ $upload->imported_rows }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Uploaded By</p>
                    <p class="text-gray-800 font-medium">{{ optional($upload->user)->name }}</p>
                </div>
            </div>

            @if($siblings->count())
                <div class="mt-6">
                    <p class="text-sm text-gray-600 mb-1">Other uploads for this date:</p>
                    <ul class="list-disc list-inside text-sm text-gray-800">
                        @foreach($siblings as $sib)
                            <li>
                                {{ $sib->pub_code }} – {{ $sib->imported_rows }} rows
                                (status: {{ $sib->status }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="mt-6 text-sm text-gray-500">
                    No other files for this publication date yet.
                </div>
            @endif

            @if($upload->combined_file_path ?? false)
                <div class="mt-4">
                    <a href="{{ route('uploads.download', [$upload->id, 'combined']) }}"
                       class="text-blue-600 hover:underline text-sm">
                        Download Combined Manifest
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Imported Rows</h3>

            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold">Truck</th>
                        <th class="px-3 py-2 text-left font-semibold">Name</th>
                        <th class="px-3 py-2 text-left font-semibold">Route</th>
                        <th class="px-3 py-2 text-left font-semibold">Type</th>
                        <th class="px-3 py-2 text-left font-semibold">Draw</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($rows as $row)
                        <tr>
                            <td class="px-3 py-2">{{ $row->truck }}</td>
                            <td class="px-3 py-2">{{ $row->name }}</td>
                            <td class="px-3 py-2">{{ $row->route }}</td>
                            <td class="px-3 py-2">{{ $row->type }}</td>
                            <td class="px-3 py-2">{{ $row->draw }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
