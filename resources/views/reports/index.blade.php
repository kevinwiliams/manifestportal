<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-sm text-gray-600">
                        Select a report type below.
                    </p>

                    <div class="divide-y divide-gray-200 border border-gray-200 rounded-md">
                        <a href="{{ route('reports.distribution') }}"
                           class="block px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        Daily Distribution Report
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Detailed manifest rows by pub date, pub code, truck, route, etc.
                                    </p>
                                </div>
                                <span class="text-gray-400 text-xs">&rarr;</span>
                            </div>
                        </a>

                        <a href="{{ route('reports.truck-summary') }}"
                           class="block px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        Truck Summary Report
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Aggregated totals per truck (stops, draw, returns).
                                    </p>
                                </div>
                                <span class="text-gray-400 text-xs">&rarr;</span>
                            </div>
                        </a>

                        <a href="{{ route('reports.dashboard') }}"
                           class="block px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        KPI Dashboard
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Visual KPIs: draw vs returns per day and per truck.
                                    </p>
                                </div>
                                <span class="text-gray-400 text-xs">&rarr;</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
