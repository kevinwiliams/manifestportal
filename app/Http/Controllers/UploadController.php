<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManifestUpload;
use App\Models\ManifestRow;
use Illuminate\Support\Facades\DB;
use App\Imports\ManifestImport;
use App\Services\FinalizeManifestService;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = ManifestUpload::orderByDesc('created_at')->paginate(20);
        return view('uploads.index', compact('uploads'));
    }

    public function create()
    {
        return view('uploads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pub_code' => 'nullable|string',
            'pub_date' => 'nullable|date',
            'file'     => 'required|file|mimes:csv,txt,xlsx',
        ]);

        // Try to auto-detect pub_code/pub_date from the file
        $meta = ManifestImport::detectMetadata($request->file('file'));

        $pubCode = $request->input('pub_code') ?: ($meta['pub_code'] ?? null);
        $pubDate = $request->input('pub_date') ?: ($meta['pub_date'] ?? null);

        if (!$pubCode || !$pubDate) {
            return back()
                ->withErrors(['upload' => 'Could not determine Publication Code or Publication Date. Please fill both fields or check file headers.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Create upload record
            $upload = ManifestUpload::create([
                'pub_code'      => $pubCode,
                'pub_date'      => $pubDate,
                'status'        => 'pending',
                'imported_rows' => 0,
                'user_id'       => auth()->id(),
            ]);

            // Store original file
            $path = $request->file('file')->store("uploads/{$upload->id}");
            $upload->stored_path = $path;
            $upload->save();

            // Import rows
            $service = new ManifestImport();
            $importCount = $service->import($upload, $request->file('file'));

            $upload->update(['imported_rows' => $importCount]);

            // Check if this completes a pair and possibly finalize
            $this->evaluateUploadStatus($upload);

            DB::commit();

            return redirect()
                ->route('uploads.show', $upload->id)
                ->with('success', "Upload complete: $importCount rows imported.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['upload' => $e->getMessage()]);
        }
    }

    /**
     * If another upload with the same date and a different pub_code exists,
     * mark them as ready and finalize the manifest.
     */
    private function evaluateUploadStatus(ManifestUpload $upload): void
    {
        $otherUpload = ManifestUpload::where('pub_date', $upload->pub_date)
            ->where('pub_code', '!=', $upload->pub_code)
            ->first();

        if (!$otherUpload) {
            // No second pub code yet – remains pending
            return;
        }

        // We have at least 2 different pub_codes for this date → mark as ready
        ManifestUpload::where('pub_date', $upload->pub_date)->update([
            'status' => 'ready',
        ]);

        // Finalize (combine data, mark processed, send email)
        FinalizeManifestService::finishForDate($upload->pub_date);
    }

    public function show(ManifestUpload $upload)
    {
        $rows = ManifestRow::where('upload_id', $upload->id)->paginate(100);

        // Other uploads on same date
        $siblings = ManifestUpload::where('pub_date', $upload->pub_date)
            ->where('id', '!=', $upload->id)
            ->get();

        return view('uploads.show', compact('upload', 'rows', 'siblings'));
    }
}
