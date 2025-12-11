<?php

namespace App\Services;

use App\Models\ManifestUpload;
use App\Models\ManifestRow;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        $filename = 'combined_manifests/manifest_' . str_replace('-', '', $pubDate) . '.csv';
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, [
            'Truck','Name','Drop Address','Route','Type','Seq',
            'Account','Group','Draw','Returns','Pub Code','Pub Date',
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

        Storage::put($filename, $csvContent);

        // Mark uploads as processed
        ManifestUpload::whereIn('id', $uploadIds)->update([
            'status'       => 'processed',
            'combined_at'  => now(),
            'combined_file_path' => $filename,
        ]);

        // Queue email (to messagequeue)
        EmailQueueService::queueManifestCompleted($pubDate, $uploads, $rows->count());
    }
}
