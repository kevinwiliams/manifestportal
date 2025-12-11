<?php

namespace App\Http\Controllers;

use App\Imports\ManifestImport;
use App\Models\ManifestRow;
use App\Models\ManifestUpload;
use App\Services\FinalizeManifestService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        // Try to auto-detect pub_code/pub_date from the uploaded file
        $meta = ManifestImport::detectMetadata($request->file('file'));

        $pubCode = $meta['pub_code'] ?? null;
        $pubDate = $meta['pub_date'] ?? null;

        if (! $pubCode || ! $pubDate) {
            return back()
                ->withErrors(['upload' => "Could not determine Publication Code or Publication Date from the uploaded file. Ensure the file contains columns like 'pub code' and 'pub date'."])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Create upload record
            $upload = ManifestUpload::create([
                'pub_code'          => $pubCode,
                'pub_date'          => $pubDate,
                'status'            => 'pending',
                'total_rows'        => 0,
                'imported_rows'     => 0,
                'skipped_rows'      => 0,
                'original_filename' => $request->file('file')->getClientOriginalName(),
                'user_id'           => auth()->id(),
            ]);

            // Store original file
            $path = $request->file('file')->store("uploads/{$upload->id}");
            $upload->stored_path = $path;
            $upload->save();

            // Import rows: instantiate with the upload and pass stored path
            // $service = new ManifestImport($upload);
            // $importCount = $service->import($upload, $path);
            Excel::import(new ManifestImport($upload), $request->file('file'));

            $upload->refresh(); // reload counts updated by ManifestImport
            $importCount = $upload->imported_rows;

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
     * Controller AJAX action: receive an uploaded file and return detected metadata as JSON.
     */
    public function detect(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        try {
            $meta = self::detectMetadata($request->file('file'));
        } catch (\Throwable $e) {
            \Log::error('[uploads.detect] detectMetadata exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to parse file for metadata: ' . $e->getMessage()], 422);
        }

        if (empty($meta)) {
            return response()->json(['meta' => null], 200);
        }

        return response()->json(['meta' => $meta], 200);
    }

    /**
     * Helper: detect pub_code and pub_date from an UploadedFile.
     */
    public static function detectMetadata(UploadedFile $file): array
    {
        try {
            // Force CSV reading
            $rows = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::CSV)[0] ?? [];
        } catch (\Throwable $e) {
            return [];
        }

        if (empty($rows)) {
            return [];
        }

        // Normalize header
        $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);

        // Find the column indexes
        $pubCodeIndex = array_search('pub code', $header);
        $pubDateIndex = array_search('pub date', $header);

        if ($pubCodeIndex === false || $pubDateIndex === false) {
            return [];
        }

        // Use first data row
        $row = $rows[1] ?? null;
        if (!$row) return [];

        $pubCode = trim($row[$pubCodeIndex] ?? '');
        $rawDate = $row[$pubDateIndex] ?? '';

        try {
            $pubDate = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
        } catch (\Throwable $e) {
            $pubDate = null;
        }

        return [
            'pub_code' => $pubCode,
            'pub_date' => $pubDate,
        ];
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

        if (! $otherUpload) {
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
