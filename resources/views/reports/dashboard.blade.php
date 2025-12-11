<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manifest KPI Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Start Date
                            </label>
                            <input type="date" name="start_date"
                                   value="{{ request('start_date', $startDate->toDateString()) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                End Date
                            </label>
                            <input type="date" name="end_date"
                                   value="{{ request('end_date', $endDate->toDateString()) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex justify-center w-full md:w-auto px-4 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Draw vs Returns by Day
                        </h3>
                        <p class="text-xs text-gray-500">
                            Between {{ $startDate->toDateString() }} and {{ $endDate->toDateString() }}
                        </p>
                    </div>
                    <div class="p-4">
                        <canvas id="perDayChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Draw vs Returns by Truck
                        </h3>
                        <p class="text-xs text-gray-500">
                            Top-level comparison by truck.
                        </p>
                    </div>
                    <div class="p-4">
                        <canvas id="perTruckChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const perDay = @json($perDay);
            const perTruck = @json($perTruck);

            const dayLabels = perDay.map(r => r.date);
            const dayDraw   = perDay.map(r => Number(r.total_draw ?? 0));
            const dayRet    = perDay.map(r => Number(r.total_returns ?? 0));

            const truckLabels = perTruck.map(r => r.truck);
            const truckDraw   = perTruck.map(r => Number(r.total_draw ?? 0));
            const truckRet    = perTruck.map(r => Number(r.total_returns ?? 0));

            new Chart(document.getElementById('perDayChart'), {
                type: 'line',
                data: {
                    labels: dayLabels,
                    datasets: [
                        {
                            label: 'Total Draw',
                            data: dayDraw,
                            borderWidth: 2,
                        },
                        {
                            label: 'Total Returns',
                            data: dayRet,
                            borderWidth: 2,
                            borderDash: [5, 5],
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            new Chart(document.getElementById('perTruckChart'), {
                type: 'bar',
                data: {
                    labels: truckLabels,
                    datasets: [
                        {
                            label: 'Total Draw',
                            data: truckDraw,
                            borderWidth: 1,
                        },
                        {
                            label: 'Total Returns',
                            data: truckRet,
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
