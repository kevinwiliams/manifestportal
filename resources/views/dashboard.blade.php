<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                <p class="text-sm text-gray-600 mt-1">Overview of upload activities and system metrics</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Last updated: {{ now()->format('M d, Y g:i A') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Uploads -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Uploads</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $summary->sum('upload_count') }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Last 30 days
                    </div>
                </div>
            </div>

            <!-- Complete Sets -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Complete Sets</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $summary->sum('processed_count') }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Ready for processing
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $summary->sum('pending_count') }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-yellow-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Awaiting completion
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-3xl font-bold text-gray-900">
                            @php
                                $total = $summary->sum('upload_count');
                                $completed = $summary->sum('processed_count');
                                $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
                            @endphp
                            {{ $rate }}%
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Date Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Date Summary Cards -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Publication Date Summary</h3>
                                <p class="text-sm text-gray-600 mt-1">Upload activity by publication date</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    {{ $summary->count() }} dates
                                </span>
                                <a href="{{ route('uploads.index') }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($summary as $row)
                            <div class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <!-- Calendar Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-lg flex flex-col items-center justify-center">
                                            <span class="text-xs font-medium text-blue-600">
                                                {{ date('M', strtotime($row->pub_date)) }}
                                            </span>
                                            <span class="text-lg font-bold text-blue-900">
                                                {{ date('d', strtotime($row->pub_date)) }}
                                            </span>
                                        </div>
                                        
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <h4 class="text-sm font-semibold text-gray-900">
                                                    {{ date('l, F d, Y', strtotime($row->pub_date)) }}
                                                </h4>
                                                <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                                    @if($row->processed_count > 0) bg-green-100 text-green-800
                                                    @elseif($row->pending_count > 0) bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($row->processed_count > 0)
                                                        Complete
                                                    @elseif($row->pending_count > 0)
                                                        In Progress
                                                    @else
                                                        New
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 mt-2">
                                                <!-- Uploads -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    <span class="text-sm text-gray-600">
                                                        {{ $row->upload_count }} uploads
                                                    </span>
                                                </div>
                                                
                                                <!-- Processed -->
                                                @if($row->processed_count > 0)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        <span class="text-sm text-green-600 font-medium">
                                                            {{ $row->processed_count }} complete
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Pending -->
                                                @if($row->pending_count > 0)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-yellow-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-sm text-yellow-600 font-medium">
                                                            {{ $row->pending_count }} pending
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end">
                                        <a href="{{ route('uploads.index', ['date' => $row->pub_date]) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                            View Details
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Progress bar -->
                                        @if($row->upload_count > 0)
                                            <div class="w-32 mt-2">
                                                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                                    <span>Completion</span>
                                                    <span>
                                                        @php
                                                            $completionRate = round(($row->processed_count / max(1, $row->upload_count)) * 100);
                                                        @endphp
                                                        {{ $completionRate }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $completionRate }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($summary->isEmpty())
                            <div class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No uploads yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Start by uploading your first manifest file.</p>
                                <div class="mt-6">
                                    <a href="{{ route('uploads.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        New Upload
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Quick Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        <p class="text-sm text-gray-600 mt-1">Common tasks</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('uploads.create') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Upload
                            </a>
                            
                            <a href="{{ route('uploads.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                View All Uploads
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">System Status</h4>
                    <div class="space-y-4">
                        <!-- Storage -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Storage</p>
                                    <p class="text-xs text-gray-500">Active and healthy</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                OK
                            </span>
                        </div>

                        <!-- Processing -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Processing</p>
                                    <p class="text-xs text-gray-500">Ready for files</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ready
                            </span>
                        </div>

                        <!-- Recent Activity -->
                        @if($summary->isNotEmpty())
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-900 mb-2">Recent Activity</p>
                                @foreach($summary->take(2) as $row)
                                    <div class="flex items-center justify-between py-2">
                                        <div>
                                            <p class="text-xs font-medium text-gray-900">
                                                {{ date('M d', strtotime($row->pub_date)) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $row->upload_count }} uploads
                                            </p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($row->processed_count > 0) bg-green-100 text-green-800
                                            @elseif($row->pending_count > 0) bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($row->processed_count > 0)
                                                Complete
                                            @else
                                                Pending
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>