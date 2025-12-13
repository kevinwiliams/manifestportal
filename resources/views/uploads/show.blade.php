<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Upload Details
                </h2>
                <div class="flex items-center space-x-4 mt-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $upload->pub_code }}
                    </span>
                    <span class="text-sm text-gray-600">
                        {{ date('F d, Y', strtotime($upload->pub_date)) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        ID: {{ $upload->id }}
                    </span>
                </div>
            </div>
            <a href="{{ route('uploads.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to History
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Status Banner -->
        <div class="mb-8">
            @if($upload->status === 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Awaiting Second Publication Code</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>This upload is pending until the second publication code for {{ date('Y-m-d', strtotime($upload->pub_date)) }} is uploaded.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($upload->status === 'completed')
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Completed – Combined Manifest Created</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Both publication codes have been uploaded and a combined manifest has been generated.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Upload Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Upload Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-semibold text-gray-900">Upload Summary</h3>
                        <p class="text-sm text-gray-600 mt-1">Details about this specific upload</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Stat Cards -->
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-600">Total Rows</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $upload->total_rows }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-600">Imported Rows</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $upload->imported_rows }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-600">Skipped Rows</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ $upload->skipped_rows ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                            <div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Publication Date</p>
                                        <div class="mt-1 flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-lg font-semibold text-gray-900">
                                                {{ date('F d, Y', strtotime($upload->pub_date)) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Publication Code</p>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $upload->pub_code }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Original Filename</p>
                                        <p class="mt-1 text-sm text-gray-900 font-medium truncate" title="{{ $upload->original_filename }}">
                                            {{ $upload->original_filename }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</p>
                                        <div class="mt-1 flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-800 font-medium text-sm">
                                                    {{ strtoupper(substr(optional($upload->user)->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ optional($upload->user)->name ?? 'Unknown User' }}
                                                </p>
                                                <p class="text-xs text-gray-500">User ID: {{ $upload->user_id }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Upload Time</p>
                                        <div class="mt-1 flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-900">
                                                {{ $upload->created_at->format('M d, Y g:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                                        <div class="mt-1">
                                            @if($upload->status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Pending
                                                </span>
                                            @elseif($upload->status === 'completed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Completed
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Combined File Download -->
                        @if($upload->combined_file_path)
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Combined Manifest Available</p>
                                        <p class="text-sm text-gray-600">Generated on {{ $upload->combined_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                    <a href="{{ route('uploads.download', [$upload->id, 'combined']) }}"
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                        Download Combined
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Imported Rows Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Imported Rows</h3>
                                <p class="text-sm text-gray-600 mt-1">All successfully imported data from this file</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $rows->total() }} Total
                            </span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Truck
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Route
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Draw
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rows as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $row->truck }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $row->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $row->route }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                @if($row->type === 'Standard') bg-green-100 text-green-800
                                                @elseif($row->type === 'Express') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $row->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $row->draw }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>

            <!-- Right Column - Related Uploads & Actions -->
            <div class="space-y-6">
                <!-- Related Uploads Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-semibold text-gray-900">Related Uploads</h3>
                        <p class="text-sm text-gray-600 mt-1">Same publication date</p>
                    </div>
                    
                    <div class="p-6">
                        @if($siblings->count())
                            <div class="space-y-4">
                                @foreach($siblings as $sibling)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($sibling->pub_code_count >= 2)
                                                    <div class="p-2 bg-green-100 rounded-lg">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('uploads.show', $sibling->id) }}" class="hover:text-blue-600">
                                                        {{ $sibling->pub_code }}
                                                    </a>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $sibling->imported_rows }} rows • 
                                                    {{ $sibling->created_at->format('M d') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                @if($sibling->status === 'completed') bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($sibling->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No other uploads</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No other files for {{ date('F d, Y', strtotime($upload->pub_date)) }} yet.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage this upload</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('uploads.index') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to History
                            </a>
                            
                            @if($upload->stored_path)
                                <a href="{{ Storage::url($upload->stored_path) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Original File
                                </a>
                            @endif
                            
                            @if($upload->combined_file_path)
                                <a href="{{ route('uploads.download', [$upload->id, 'combined']) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-green-700 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                    Download Combined
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-6">
                    <h4 class="text-sm font-semibold text-blue-900 mb-4">Completion Progress</h4>
                    <div class="space-y-4">
                        @php
                            $totalSiblings = $siblings->count() + 1; // +1 for current upload
                            $completeSiblings = $siblings->where('pub_code_count', '>=', 2)->count();
                            if($upload->pub_code_count >= 2) $completeSiblings++;
                        @endphp
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-blue-700">Publication Codes</span>
                                <span class="text-xs font-semibold text-blue-900">{{ $completeSiblings }}/2</span>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($completeSiblings/2)*100) }}%"></div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            @if($completeSiblings >= 2)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Ready for Merge
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ 2 - $completeSiblings }} more needed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>