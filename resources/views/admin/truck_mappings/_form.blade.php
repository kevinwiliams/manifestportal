@csrf

<div class="space-y-6">

    <div>
         <label class="block text-sm font-medium mb-1">Original Truck Code</label>
         <input type="text" name="source_code" value="{{ old('source_code', $mapping->source_code ?? '') }}"
             class="w-full border-gray-300 rounded" required>
        </div>

        <div>
         <label class="block text-sm font-medium mb-1">Map To (Standard Truck Code)</label>
         <input type="text" name="target_code" value="{{ old('target_code', $mapping->target_code ?? '') }}"
             class="w-full border-gray-300 rounded" required>
        </div>

        <div>
         <label class="block text-sm font-medium mb-1">Description (Optional)</label>
         <input type="text" name="description" value="{{ old('description', $mapping->description ?? '') }}"
             class="w-full border-gray-300 rounded">
        </div>

        <div class="flex items-center">
         <input type="checkbox" name="is_active" id="is_active" value="1" 
             @checked(old('is_active', $mapping->is_active ?? true))
             class="rounded border-gray-300">
         <label for="is_active" class="ml-2 text-sm font-medium">Active</label>
    </div>

    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Save Mapping
    </button>

</div>
