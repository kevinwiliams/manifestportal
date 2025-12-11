<?php

namespace App\Exports;

use App\Models\ManifestRow;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TruckSummaryReportExport implements FromCollection, Responsable, WithHeadings
{
    public string $fileName = 'truck-summary-report.xlsx';

    protected ?string $pubDate;

    protected ?string $pubCode;

    public function __construct(array $filters = [])
    {
        $this->pubDate = $filters['pub_date'] ?? null;
        $this->pubCode = $filters['pub_code'] ?? null;

        if ($this->pubDate) {
            $this->pubDate = Carbon::parse($this->pubDate)->format('Y-m-d');
        }
    }

    public function headings(): array
    {
        return [
            'Truck',
            'Total Stops',
            'Total Draw',
            'Total Returns',
        ];
    }

    public function collection()
    {
        $query = ManifestRow::query();

        if ($this->pubDate) {
            $query->whereDate('pub_date', $this->pubDate);
        }

        if ($this->pubCode) {
            $query->where('pub_code', $this->pubCode);
        }

        return $query
            ->selectRaw('
                truck,
                COUNT(*) as total_stops,
                SUM(CAST(draw AS INT)) as total_draw,
                SUM(CAST(returns AS INT)) as total_returns
            ')
            ->groupBy('truck')
            ->orderBy('truck')
            ->get();
    }
}
