<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Upload Manifest File</h2>
                <p class="text-sm text-gray-600 mt-1">Add new manifest data to the system</p>
            </div>
            <a href="{{ route('uploads.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Uploads
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Progress indicator -->
        <div class="mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">1</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Upload Manifest</p>
                    <p class="text-sm text-gray-500">Select and upload your manifest file</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-4">
            <!-- Form header -->
            <div class="px-8 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">File Upload Form</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Publication Code and Date will be automatically extracted from the uploaded file
                </p>
            </div>

            <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data" class="px-8 py-6">
                @csrf

                <!-- Error display -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-red-800">Please fix the following errors:</h4>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-8">
                    <!-- File upload section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900">Manifest File</label>
                                <p class="text-sm text-gray-500 mt-1">Supported formats: CSV, Excel</p>
                            </div>
                            <div class="text-xs px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                Required
                            </div>
                        </div>

                        <!-- Enhanced file upload area -->
                        <div class="relative group">
                            <label class="flex flex-col items-center justify-center w-full h-56 border-3 border-dashed rounded-2xl cursor-pointer transition-all duration-200
                                        border-blue-300 bg-gradient-to-br from-blue-50 to-white
                                        hover:border-blue-400 hover:bg-blue-50 hover:shadow-md
                                        focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 focus-within:ring-offset-2">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4">
                                    <!-- Icon container -->
                                    <div class="mb-4 p-4 bg-blue-100 rounded-full group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Text content -->
                                    <div class="text-center">
                                        <p class="text-base font-medium text-gray-700 mb-1">
                                            <span class="text-blue-600">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-sm text-gray-500 mb-3">
                                            CSV or Excel files (MAX. 10MB)
                                        </p>
                                        <div class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                            </svg>
                                            Choose File
                                        </div>
                                    </div>
                                </div>
                                
                                <input id="manifest-file-input" 
                                       type="file" 
                                       name="file" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       required
                                       accept=".csv,.xlsx,.xls" />
                            </label>
                        </div>

                        <!-- File preview area - enhanced -->
                        <div id="file-preview" class="mt-6" aria-live="polite">
                            <!-- Loading state -->
                            <div id="preview-loading" class="hidden">
                                <div class="flex items-center justify-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <svg class="animate-spin w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700">Analyzing file metadata...</span>
                                </div>
                            </div>

                            <!-- Success result -->
                            <div id="preview-result" class="hidden">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-semibold text-green-800 mb-2">Metadata Detected Successfully</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-white rounded-lg p-3 border border-green-100">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Publication Code</p>
                                                    <p class="text-lg font-semibold text-gray-900" id="preview-pub-code"></p>
                                                </div>
                                                <div class="bg-white rounded-lg p-3 border border-green-100">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Publication Date</p>
                                                    <p class="text-lg font-semibold text-gray-900" id="preview-pub-date"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No metadata -->
                            <div id="preview-none" class="hidden">
                                <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">No metadata detected</p>
                                        <p class="text-sm text-yellow-700">Publication code and date will need to be entered manually after upload</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Error state -->
                            <div id="preview-error" class="hidden">
                                <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Could not parse file</p>
                                        <p class="text-sm text-red-700">Please ensure the file is a valid CSV or Excel format</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <p>File will be processed automatically after upload</p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="window.history.back()"
                                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                        Upload File
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help information -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-sm font-semibold text-blue-900">File Requirements</h4>
                </div>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Maximum file size: 10MB</li>
                    <li>• Supported formats: .csv, .xlsx, .xls</li>
                    <li>• Include manifest data columns</li>
                </ul>
            </div>

            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-sm font-semibold text-green-900">Metadata Extraction</h4>
                </div>
                <p class="text-xs text-green-700">
                    Publication code and date are automatically extracted from filename or file content. No manual entry required.
                </p>
            </div>

            <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h4 class="text-sm font-semibold text-purple-900">Secure Processing</h4>
                </div>
                <p class="text-xs text-purple-700">
                    Files are processed securely and stored with encryption. All uploads are logged for audit purposes.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Upload preview script (robust init so it logs even if script loads after DOMContentLoaded)
        function initUploadPreview() {
            console.log('[upload] initUploadPreview running');
            const input = document.getElementById('manifest-file-input');
            const loading = document.getElementById('preview-loading');
            const result = document.getElementById('preview-result');
            const none = document.getElementById('preview-none');
            const err = document.getElementById('preview-error');
            const pubCodeEl = document.getElementById('preview-pub-code');
            const pubDateEl = document.getElementById('preview-pub-date');

            if (!input) {
                console.warn('[upload] manifest-file-input not found in DOM');
                return;
            }

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
        }

        // Initialize on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initUploadPreview);
        } else {
            initUploadPreview();
        }
    </script>

</x-app-layout>

