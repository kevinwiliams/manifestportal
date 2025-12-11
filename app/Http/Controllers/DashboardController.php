<?php
namespace App\Http\Controllers;

use App\Models\ManifestUpload;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $summary = ManifestUpload::select(
    //             'pub_date',
    //             // DISTINCT pub codes using STUFF + FOR XML
    //             DB::raw("
    //                 STUFF((
    //                     SELECT DISTINCT ',' + mu2.pub_code
    //                     FROM manifest_uploads mu2
    //                     WHERE mu2.pub_date = manifest_uploads.pub_date
    //                     FOR XML PATH(''), TYPE
    //                 ).value('.', 'NVARCHAR(MAX)'), 1, 1, '') AS pub_codes
    //             "),
    //             DB::raw("COUNT(DISTINCT pub_code) as pub_code_count"),
    //             DB::raw("SUM(imported_rows) as total_imported")
    //         )
    //         ->groupBy('pub_date')
    //         ->orderByDesc('pub_date')
    //         ->get();
    
    //     return view('dashboard', compact('summary'));
    // }

    public function index()
    {
        $summary = ManifestUpload::select(
                'pub_date',
                DB::raw('COUNT(*) as upload_count'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
                DB::raw("SUM(CASE WHEN status = 'processed' THEN 1 ELSE 0 END) as processed_count")
            )
            ->groupBy('pub_date')
            ->orderByDesc('pub_date')
            ->limit(30)
            ->get();

        return view('dashboard', compact('summary'));
    }
    
}
