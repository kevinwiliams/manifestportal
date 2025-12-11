<?php

namespace App\Http\Controllers;

use App\Models\TruckMapping;
use Illuminate\Http\Request;

class TruckMappingController extends Controller
{
    public function index()
    {
        $mappings = TruckMapping::orderBy('source_code')->paginate(50);

        return view('admin.truck_mappings.index', compact('mappings'));
    }

    public function create()
    {
        $mapping = new TruckMapping;

        return view('admin.truck_mappings.create', compact('mapping'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'source_code' => ['required', 'string', 'max:20', 'unique:truck_mappings,source_code'],
            'target_code' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['source_code'] = strtoupper(trim($data['source_code']));
        $data['target_code'] = strtoupper(trim($data['target_code']));
        $data['is_active'] = $request->boolean('is_active');

        TruckMapping::create($data);

        return redirect()
            ->route('admin.truck-mappings.index')
            ->with('status', 'Truck mapping created successfully.');
    }

    public function edit(TruckMapping $truck_mapping)
    {
        $mapping = $truck_mapping;

        return view('admin.truck_mappings.edit', compact('mapping'));
    }

    public function update(Request $request, TruckMapping $truck_mapping)
    {
        $data = $request->validate([
            'source_code' => ['required', 'string', 'max:20', 'unique:truck_mappings,source_code,'.$truck_mapping->id],
            'target_code' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['source_code'] = strtoupper(trim($data['source_code']));
        $data['target_code'] = strtoupper(trim($data['target_code']));
        $data['is_active'] = $request->boolean('is_active');

        $truck_mapping->update($data);

        return redirect()
            ->route('admin.truck-mappings.index')
            ->with('status', 'Truck mapping updated successfully.');
    }

    public function destroy(TruckMapping $truck_mapping)
    {
        $truck_mapping->delete();

        return redirect()
            ->route('admin.truck-mappings.index')
            ->with('status', 'Truck mapping deleted.');
    }
}
