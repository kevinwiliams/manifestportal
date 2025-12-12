<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto grid md:grid-cols-3 gap-6">
        @foreach($summary as $row)
            <div class="bg-white shadow rounded p-5">
                <div class="text-sm text-gray-500">Publication Date</div>
                <div class="text-lg font-semibold mb-2">{{ date('d-M-y', strtotime($row->pub_date)) }}</div>

                <div class="text-sm text-gray-600">
                    Uploads: {{ $row->upload_count }}<br>
                    Pending: {{ $row->pending_count }}<br>
                    Processed: {{ $row->processed_count }}
                </div>

                <a href="{{ route('uploads.index', ['date' => $row->pub_date]) }}"
                   class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                    View uploads
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
