@csrf

<div class="space-y-6">

    <div>
        <label class="block text-sm font-medium mb-1">Original Truck Code</label>
        <input type="text" name="original" value="{{ old('original', $mapping->original ?? '') }}"
               class="w-full border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Map To (Standard Truck Code)</label>
        <input type="text" name="mapped_to" value="{{ old('mapped_to', $mapping->mapped_to ?? '') }}"
               class="w-full border-gray-300 rounded" required>
    </div>

    <button class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Save Mapping
    </button>

</div>
