<?php

namespace App\Imports;

use App\Models\ManifestRow;
use App\Models\ManifestUpload;
use App\Models\TruckMapping;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class ManifestImport implements ToCollection, WithHeadingRow
{
    protected ManifestUpload $upload;

    protected int $imported = 0;

    protected int $skipped = 0;

    /** @var array<string,string> */
    protected array $truckMap = [];

    public function __construct(ManifestUpload $upload)
    {
        $this->upload = $upload;

        // Load all active mappings into an array: ['DEV1' => 'DEV-01', ...]
        $this->truckMap = TruckMapping::active()
            ->get()
            ->pluck('target_code', 'source_code')
            ->mapWithKeys(function ($target, $source) {
                return [strtoupper(trim($source)) => strtoupper(trim($target))];
            })
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->upload->total_rows++;

            // --- normalize fields as before ---
            $truck = strtoupper(trim($row['truck'] ?? ''));

            $name = trim($row['name'] ?? $row['agent_name'] ?? '');
            $dropAddress = trim($row['drop_address'] ?? $row['address'] ?? '');
            $route = trim($row['route'] ?? $row['route_id'] ?? '');
            $type = strtoupper(trim($row['type'] ?? ''));
            $seq = $row['seq'] ?? null;
            $account = trim($row['account'] ?? '');
            $group = trim($row['group'] ?? '');
            $draw = $row['draw'] ?? null;
            // handle duplicate "returns" header
            $returns = $row['returns'] ?? $row['returns_1'] ?? null;

            $pubCode = trim($row['pub_code'] ?? $this->upload->pub_code);
            $pubDate = $this->upload->pub_date;
            if (!empty($row['pub_date'])) {
                try {
                    $pubDate = \Carbon\Carbon::parse($row['pub_date']);
                } catch (\Throwable $e) {}
}

            $truckDescr = trim($row['truck_descr'] ?? '');
            $dropInstructions = trim($row['drop_instructions'] ?? '');

            // --- skip rules ---
            if ($route === '') {
                $this->skipped++;

                continue;
            }

            if ($truck === 'SUBC') {
                $this->skipped++;

                continue;
            }

            if ($type === 'SUBSCRIPTION CARRIER') {
                $this->skipped++;

                continue;
            }

            // --- DB-based truck mapping ---
            $mappedTruck = $this->truckMap[$truck] ?? $truck;

            // dedupe
            if ($account !== '') {
                $exists = ManifestRow::where('pub_date', $pubDate)
                    ->where('pub_code', $pubCode)
                    ->where('route', $route)
                    ->exists();

                if ($exists) {
                    $this->skipped++;

                    continue;
                }
            }

            ManifestRow::create([
                'upload_id' => $this->upload->id,
                'truck' => $mappedTruck,
                'name' => $name,
                'drop_address' => $dropAddress,
                'route' => $route,
                'type' => $type,
                'seq' => $seq,
                'account' => $account,
                'group' => $group,
                'draw' => $draw,
                'returns' => $returns,
                'pub_code' => $pubCode,
                'pub_date' => $pubDate,
                'truck_descr' => $truckDescr,
                'drop_instructions' => $dropInstructions,
            ]);

            $this->imported++;
        }

        $this->upload->update([
            'total_rows' => $this->upload->total_rows,
            'imported_rows' => $this->imported,
            'skipped_rows' => $this->skipped,
            'status' => 'completed',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pub_code' => 'required',
            'pub_date' => 'required|date',
            'file' => 'required|file',
        ]);

        $upload = new ManifestUpload;
        $upload->pub_code = $request->pub_code;
        $upload->pub_date = $request->pub_date;
        $upload->status = 'pending';
        $upload->user_id = auth()->id();
        $upload->save();

        // store file
        $path = $request->file('file')->store("uploads/{$upload->id}");
        $upload->file1_path = $path;
        $upload->save();

        // Import rows
        $importer = new ManifestImport($upload);
        $importer->process();

        // Determine if second code exists
        $this->checkForCompletion($upload);

        return redirect()->route('uploads.show', $upload->id);
    }

    public static function detectMetadata(UploadedFile $file): array
    {
        $sheets = Excel::toArray([], $file);
        if (empty($sheets) || empty($sheets[0])) {
            return [];
        }

        $rows = $sheets[0];
        $header = $rows[0];

        $pubCodeIndex = null;
        $pubDateIndex = null;

        foreach ($header as $i => $col) {
            $colLower = strtolower(trim((string) $col));
            if (str_contains($colLower, 'pub code')) {
                $pubCodeIndex = $i;
            }
            if (str_contains($colLower, 'pub date')) {
                $pubDateIndex = $i;
            }
        }

        if ($pubCodeIndex === null && $pubDateIndex === null) {
            return [];
        }

        // look at the first non-empty data row
        $dataRow = $rows[1] ?? null;
        if (! $dataRow) {
            return [];
        }

        $meta = [];

        if ($pubCodeIndex !== null) {
            $meta['pub_code'] = trim((string) ($dataRow[$pubCodeIndex] ?? ''));
        }

        if ($pubDateIndex !== null) {
            $rawDate = $dataRow[$pubDateIndex] ?? null;
            if ($rawDate instanceof \DateTimeInterface) {
                $meta['pub_date'] = $rawDate->format('Y-m-d');
            } else {
                // Try to parse a string date like 12/1/25
                try {
                    $dt = \Carbon\Carbon::parse($rawDate);
                    $meta['pub_date'] = $dt->format('Y-m-d');
                } catch (\Throwable $e) {
                    // ignore parse error
                }
            }
        }

        return $meta;
    }

    private function isHeaderRow($row)
    {
        $possibleHeaders = ['truck', 'agent name', 'name', 'route', 'pub date', 'drop address'];

        foreach ($row as $value) {
            if (! $value) {
                continue;
            }
            $lower = strtolower(trim($value));
            foreach ($possibleHeaders as $header) {
                if (str_contains($lower, $header)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function import($upload, $path)
    {
        $rows = Excel::toArray([], storage_path("app/{$path}"))[0];
        $count = 0;

        foreach ($rows as $i => $r) {

            // skip empty rows
            if (count(array_filter($r)) == 0) {
                continue;
            }

            // auto detect header row
            if ($i == 0 && $this->isHeaderRow($r)) {
                continue;
            }

            // normalize indexes in case the user uploads XLSX with shifted columns
            $truck = trim($r[0] ?? '');

            // Skip SUBC truck
            if (strtoupper($truck) === 'SUBC') {
                continue;
            }

            $type = trim($r[4] ?? '');

            // Skip SUBSCRIPTION CARRIER
            if (strtoupper($type) === 'SUBSCRIPTION CARRIER') {
                continue;
            }

            $route = trim($r[3] ?? '');
            if ($route === '') {
                continue;
            }

            $account = trim($r[6] ?? '');

            // Prevent duplicates (pubdate + account)
            if (ManifestRow::where('pub_date', $upload->pub_date)
                ->where('account', $account)
                ->exists()) {
                continue;
            }

            ManifestRow::create([
                'upload_id' => $upload->id,
                'truck' => $truck,
                'name' => $r[1] ?? '',
                'drop_address' => $r[2] ?? '',
                'route' => $route,
                'type' => $type,
                'seq' => $r[5] ?? null,
                'account' => $account,
                'group' => $r[7] ?? '',
                'draw' => $r[8] ?? 0,
                'returns' => $r[9] ?? 0,
                'pub_code' => $upload->pub_code,
                'pub_date' => $upload->pub_date,
                'truck_descr' => $r[12] ?? null,
                'drop_instructions' => $r[13] ?? null,
            ]);

            $count++;
        }

        return $count;
    }
}
