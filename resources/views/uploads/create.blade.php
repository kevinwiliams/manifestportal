<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Upload Manifest File</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white shadow rounded p-6">

        <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">

                <div>
                    <p class="text-sm text-gray-600 mb-1">Publication Code and Date will be extracted from the file</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Manifest File</label>

                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        <span class="text-gray-500 text-sm">Drop file here or click to upload</span>
                        <input id="manifest-file-input" type="file" name="file" class="hidden" required />
                    </label>

                    <div id="file-preview" class="mt-3 text-sm text-gray-700" aria-live="polite">
                        <div id="preview-loading" class="hidden">Detecting metadata…</div>
                        <div id="preview-result" class="hidden">
                            <div><strong>Publication Code:</strong> <span id="preview-pub-code"></span></div>
                            <div><strong>Publication Date:</strong> <span id="preview-pub-date"></span></div>
                        </div>
                        <div id="preview-none" class="text-yellow-600 hidden">No metadata detected in file.</div>
                        <div id="preview-error" class="text-red-600 hidden">Could not parse the file for metadata.</div>
                    </div>
                </div>

                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Upload File
                </button>
            </div>

        </form>

    </div>

</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('manifest-file-input');
    const loading = document.getElementById('preview-loading');
    const result = document.getElementById('preview-result');
    const none = document.getElementById('preview-none');
    const err = document.getElementById('preview-error');
    const pubCodeEl = document.getElementById('preview-pub-code');
    const pubDateEl = document.getElementById('preview-pub-date');

    if (!input) return;

    input.addEventListener('change', async function (e) {
        const file = input.files && input.files[0];
        if (!file) return;

        // reset
        loading.classList.remove('hidden');
        result.classList.add('hidden');
        none.classList.add('hidden');
        err.classList.add('hidden');

        const fd = new FormData();
        fd.append('file', file);

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch("{{ route('uploads.detect') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
                body: fd,
            });

            loading.classList.add('hidden');

            if (!res.ok) {
                err.classList.remove('hidden');
                return;
            }

            const data = await res.json();
            if (!data.meta) {
                none.classList.remove('hidden');
                return;
            }

            pubCodeEl.textContent = data.meta.pub_code || '';
            pubDateEl.textContent = data.meta.pub_date || '';
            result.classList.remove('hidden');

        } catch (e) {
            console.error('Metadata detection error:', e);
            loading.classList.add('hidden');
            err.classList.remove('hidden');
        }
    });
});
</script>
@endpush
