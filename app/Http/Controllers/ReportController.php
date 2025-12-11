<?php

namespace App\Http\Controllers;

use App\Exports\DistributionReportExport;
use App\Exports\TruckSummaryReportExport;
use App\Models\ManifestRow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function distribution(Request $request)
    {
        $pubDate = $request->pub_date
            ? Carbon::parse($request->pub_date)->format('Y-m-d')
            : null;

        $pubCode = $request->pub_code;

        $query = ManifestRow::query();

        if ($pubDate) {
            $query->whereDate('pub_date', $pubDate);
        }

        if ($pubCode) {
            $query->where('pub_code', $pubCode);
        }

        if ($request->truck) {
            $query->where('truck', $request->truck);
        }

        if ($request->route) {
            $query->where('route', $request->route);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $rows = $query->orderBy('truck')->orderBy('seq')->paginate(300)->withQueryString();

        return view('reports.distribution', [
            'rows'    => $rows,
            'filters' => $request->all(),
        ]);
    }

    public function distributionExportXlsx(Request $request)
    {
        return new DistributionReportExport($request->all());
    }

    public function distributionExportPdf(Request $request)
    {
        $pubDate = $request->pub_date
            ? Carbon::parse($request->pub_date)->format('Y-m-d')
            : null;

        $pubCode = $request->pub_code;

        $query = ManifestRow::query();

        if ($pubDate) {
            $query->whereDate('pub_date', $pubDate);
        }

        if ($pubCode) {
            $query->where('pub_code', $pubCode);
        }

        if ($request->truck) {
            $query->where('truck', $request->truck);
        }

        if ($request->route) {
            $query->where('route', $request->route);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $rows = $query->orderBy('truck')->orderBy('seq')->get();

        $pdf = Pdf::loadView('reports.pdf.distribution', [
            'rows'    => $rows,
            'filters' => $request->all(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('distribution-report.pdf');
    }

    public function truckSummary(Request $request)
    {
        $pubDate = $request->pub_date
            ? Carbon::parse($request->pub_date)->format('Y-m-d')
            : null;

        $pubCode = $request->pub_code;

        $query = ManifestRow::query();

        if ($pubDate) {
            $query->whereDate('pub_date', $pubDate);
        }

        if ($pubCode) {
            $query->where('pub_code', $pubCode);
        }

        $summary = $query->selectRaw("
                truck,
                COUNT(*) as total_stops,
                SUM(CAST(draw AS INT)) as total_draw,
                SUM(CAST(returns AS INT)) as total_returns
            ")
            ->groupBy('truck')
            ->orderBy('truck')
            ->get();

        return view('reports.truck_summary', [
            'summary' => $summary,
            'filters' => $request->all(),
        ]);
    }

    public function truckSummaryExportXlsx(Request $request)
    {
        return new TruckSummaryReportExport($request->all());
    }

    public function truckSummaryExportPdf(Request $request)
    {
        $pubDate = $request->pub_date
            ? Carbon::parse($request->pub_date)->format('Y-m-d')
            : null;

        $pubCode = $request->pub_code;

        $query = ManifestRow::query();

        if ($pubDate) {
            $query->whereDate('pub_date', $pubDate);
        }

        if ($pubCode) {
            $query->where('pub_code', $pubCode);
        }

        $summary = $query->selectRaw("
                truck,
                COUNT(*) as total_stops,
                SUM(CAST(draw AS INT)) as total_draw,
                SUM(CAST(returns AS INT)) as total_returns
            ")
            ->groupBy('truck')
            ->orderBy('truck')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.truck_summary', [
            'summary' => $summary,
            'filters' => $request->all(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('truck-summary-report.pdf');
    }

    /**
     * KPI Dashboard with graphs
     */
    public function dashboard(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(7)->startOfDay();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        // per-day totals
        $perDay = ManifestRow::selectRaw("
                CONVERT(date, pub_date) as date,
                SUM(CAST(draw AS INT)) as total_draw,
                SUM(CAST(returns AS INT)) as total_returns
            ")
            ->whereBetween('pub_date', [$startDate, $endDate])
            ->groupBy(DB::raw('CONVERT(date, pub_date)'))
            ->orderBy(DB::raw('CONVERT(date, pub_date)'))
            ->get();

        $perTruck = ManifestRow::selectRaw("
                truck,
                SUM(CAST(draw AS INT)) as total_draw,
                SUM(CAST(returns AS INT)) as total_returns
            ")
            ->whereBetween('pub_date', [$startDate, $endDate])
            ->groupBy('truck')
            ->orderBy('truck')
            ->get();

        return view('reports.dashboard', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'perDay'    => $perDay,
            'perTruck'  => $perTruck,
        ]);
    }
}
