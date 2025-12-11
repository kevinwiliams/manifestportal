<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Upload Manifest File</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white shadow rounded p-6">

        <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium mb-1">Publication Code</label>
                    <input type="text" name="pub_code"
                           class="w-full border-gray-300 rounded"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Publication Date</label>
                    <input type="date" name="pub_date"
                           class="w-full border-gray-300 rounded"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Manifest File</label>

                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        <span class="text-gray-500 text-sm">Drop file here or click to upload</span>
                        <input type="file" name="file" class="hidden" required />
                    </label>
                </div>

                <button class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Upload File
                </button>
            </div>

        </form>

    </div>

</x-app-layout>
