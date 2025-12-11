<?php

namespace App\Services;

use App\Models\ManifestRow;
use App\Models\ManifestUpload;
use Illuminate\Support\Facades\Storage;

class FinalizeManifestService
{
    /**
     * Called once 2 different pub_codes exist for a given pub_date.
     * - marks uploads as processed
     * - writes combined CSV
     * - queues notification email
     */
    public static function finishForDate(string $pubDate): void
    {
        // Get all uploads for that date
        $uploads = ManifestUpload::where('pub_date', $pubDate)->get();
        if ($uploads->count() < 2) {
            return; // nothing to do
        }

        $uploadIds = $uploads->pluck('id');

        // Fetch all rows for the date
        $rows = ManifestRow::whereIn('upload_id', $uploadIds)
            ->orderBy('truck')
            ->orderBy('route')
            ->orderBy('seq')
            ->get();

        // Write combined CSV file
        // Ensure pubDate is in YYYY-MM-DD format (may come in as Carbon object)
        $pubDateFormatted = is_string($pubDate) ? $pubDate : $pubDate->format('Y-m-d');
        $filename = 'combined_manifests/manifest_'.str_replace('-', '', $pubDateFormatted).'.csv';
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, [
            'Truck', 'Name', 'Drop Address', 'Route', 'Type', 'Seq',
            'Account', 'Group', 'Draw', 'Returns', 'Pub Code', 'Pub Date',
        ]);

        foreach ($rows as $r) {
            fputcsv($handle, [
                $r->truck,
                $r->name,
                $r->drop_address,
                $r->route,
                $r->type,
                $r->seq,
                $r->account,
                $r->group,
                $r->draw,
                $r->returns,
                $r->pub_code,
                $r->pub_date,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        // Ensure the directory exists
        Storage::makeDirectory('combined_manifests', 0755, true, true);
        Storage::put($filename, $csvContent);

        // Mark uploads as completed
        ManifestUpload::whereIn('id', $uploadIds)->update([
            'status' => 'completed',
            'combined_at' => now(),
            'combined_file_path' => $filename,
        ]);

        // Queue email (to messagequeue)
        EmailQueueService::queueManifestCompleted($pubDate, $uploads, $rows->count());
    }
}
