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

                    <label class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        <span class="text-gray-500 text-sm">Drop file here or click to upload</span>
                        <input id="manifest-file-input" type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required />
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

        console.log('[upload] file selected:', { name: file.name, size: file.size, type: file.type, lastModified: file.lastModified });

        // If it's a text/csv file, read a small preview for debugging
        if (/\.(csv|txt)$/i.test(file.name) || file.type === 'text/csv' || file.type === 'text/plain') {
            const reader = new FileReader();
            reader.onload = function (ev) {
                const txt = (ev.target.result || '').toString();
                console.log('[upload] file preview (first 1000 chars):', txt.slice(0, 1000));
            };
            reader.onerror = function (err) {
                console.warn('[upload] file preview read error', err);
            };
            // read a small portion
            try {
                reader.readAsText(file.slice(0, 1024 * 50));
            } catch (readErr) {
                console.warn('[upload] file preview read exception', readErr);
            }
        }

        // reset
        loading.classList.remove('hidden');
        result.classList.add('hidden');
        none.classList.add('hidden');
        err.classList.add('hidden');

        const fd = new FormData();
        fd.append('file', file);

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // For debugging, log FormData entries if supported
            try {
                for (const pair of fd.entries()) {
                    console.log('[upload][detect] formData entry:', pair[0], pair[1]);
                }
            } catch (fdErr) {
                console.warn('[upload][detect] could not enumerate FormData entries', fdErr);
            }

            const res = await fetch("{{ route('uploads.detect') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
                body: fd,
            });

            console.log('[upload][detect] fetch response status:', res.status, res.statusText);

            loading.classList.add('hidden');

            if (!res.ok) {
                // try to show error body for debugging
                try {
                    const txt = await res.text();
                    console.error('[upload][detect] non-ok response body:', txt);
                } catch (readErr) {
                    console.error('[upload][detect] non-ok and could not read body', readErr);
                }
                err.classList.remove('hidden');
                return;
            }

            const data = await res.json();
            console.log('[upload][detect] response json:', data);
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

    // Log form submit contents so we can see what will be sent to the server
    const form = document.querySelector('form[action="{{ route('uploads.store') }}"]');
    if (form) {
        form.addEventListener('submit', function (ev) {
            try {
                const sfd = new FormData(form);
                console.log('[upload][submit] form submit - enumerating fields:');
                for (const pair of sfd.entries()) {
                    if (pair[1] instanceof File) {
                        console.log('[upload][submit] field:', pair[0], 'File:', { name: pair[1].name, size: pair[1].size, type: pair[1].type });
                    } else {
                        console.log('[upload][submit] field:', pair[0], pair[1]);
                    }
                }
            } catch (err) {
                console.warn('[upload][submit] could not enumerate form data', err);
            }
            // allow form to submit normally after logging
        });
    } else {
        console.warn('[upload] upload form not found for submit logging');
    }
});
</script>
@endpush
