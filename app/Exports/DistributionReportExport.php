<?php

namespace App\Exports;

use App\Models\ManifestRow;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistributionReportExport implements FromCollection, Responsable, WithHeadings
{
    public string $fileName = 'distribution-report.xlsx';

    protected ?string $pubDate;

    protected ?string $pubCode;

    protected ?string $truck;

    protected ?string $route;

    protected ?string $type;

    public function __construct(array $filters = [])
    {
        $this->pubDate = $filters['pub_date'] ?? null;
        $this->pubCode = $filters['pub_code'] ?? null;
        $this->truck = $filters['truck'] ?? null;
        $this->route = $filters['route'] ?? null;
        $this->type = $filters['type'] ?? null;

        if ($this->pubDate) {
            $this->pubDate = Carbon::parse($this->pubDate)->format('Y-m-d');
        }
    }

    public function headings(): array
    {
        return [
            'Truck',
            'Seq',
            'Name',
            'Address',
            'Route',
            'Type',
            'Draw',
            'Returns',
            'Pub Code',
            'Pub Date',
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

        if ($this->truck) {
            $query->where('truck', $this->truck);
        }

        if ($this->route) {
            $query->where('route', $this->route);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        return $query->orderBy('truck')
            ->orderBy('seq')
            ->get([
                'truck',
                'seq',
                'name',
                'drop_address',
                'route',
                'type',
                'draw',
                'returns',
                'pub_code',
                'pub_date',
            ]);
    }
}
