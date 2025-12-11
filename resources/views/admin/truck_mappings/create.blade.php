<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Add Truck Mapping</h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white shadow rounded p-6">
        <form action="{{ route('admin.truck-mappings.store') }}" method="POST">
            @include('admin.truck_mappings._form')
        </form>
    </div>

</x-app-layout>
