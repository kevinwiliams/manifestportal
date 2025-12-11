<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Distribution Report') }}
            </h2>

            @if($rows->count())
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.distribution.export.xlsx', $filters) }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md border border-emerald-500 text-emerald-700 hover:bg-emerald-50">
                        Export XLSX
                    </a>
                    <a href="{{ route('reports.distribution.export.pdf', $filters) }}"
                       target="_blank"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md border border-gray-400 text-gray-700 hover:bg-gray-50">
                        Print PDF
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Pub Date
                            </label>
                            <input type="date" name="pub_date"
                                   value="{{ $filters['pub_date'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Pub Code
                            </label>
                            <input type="text" name="pub_code"
                                   value="{{ $filters['pub_code'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Truck
                            </label>
                            <input type="text" name="truck"
                                   value="{{ $filters['truck'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Route
                            </label>
                            <input type="text" name="route"
                                   value="{{ $filters['route'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm" />
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex justify-center w-full md:w-auto px-4 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-0 overflow-x-auto">
                    @if($rows->count() === 0)
                        <div class="p-6 text-sm text-gray-500">
                            No records found for the selected filters.
                        </div>
                    @else
                        <table class="min-w-full text-xs border-t border-gray-200">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Truck</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Seq</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Address</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Route</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700">Type</th>
                                    <th class="px-3 py-2 text-right font-semibold text-gray-700">Draw</th>
                                    <th class="px-3 py-2 text-right font-semibold text-gray-700">Returns</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($rows as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-800">{{ $row->truck }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-800">{{ $row->seq }}</td>
                                        <td class="px-3 py-2 text-gray-800">{{ $row->name }}</td>
                                        <td class="px-3 py-2 text-gray-700">{{ $row->drop_address }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-800">{{ $row->route }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-800">{{ $row->type }}</td>
                                        <td class="px-3 py-2 text-right text-gray-800">{{ $row->draw }}</td>
                                        <td class="px-3 py-2 text-right text-gray-800">{{ $row->returns }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="px-4 py-3 border-t border-gray-200">
                            {{ $rows->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
