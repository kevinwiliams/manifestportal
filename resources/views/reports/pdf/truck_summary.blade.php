<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Truck Summary Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px; }
        th { background: #eee; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .meta { font-size: 11px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Truck Summary Report</h1>
    <div class="meta">
        @if(!empty($filters['pub_date']))
            <strong>Pub Date:</strong> {{ $filters['pub_date'] }}&nbsp;&nbsp;
        @endif
        @if(!empty($filters['pub_code']))
            <strong>Pub Code:</strong> {{ $filters['pub_code'] }}&nbsp;&nbsp;
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Truck</th>
                <th>Total Stops</th>
                <th>Total Draw</th>
                <th>Total Returns</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $row)
                <tr>
                    <td>{{ $row->truck }}</td>
                    <td>{{ $row->total_stops }}</td>
                    <td>{{ $row->total_draw }}</td>
                    <td>{{ $row->total_returns }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
