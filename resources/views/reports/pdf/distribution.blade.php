<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Distribution Report</title>
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
    <h1>Daily Distribution Report</h1>
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
                <th>Seq</th>
                <th>Name</th>
                <th>Address</th>
                <th>Route</th>
                <th>Type</th>
                <th>Draw</th>
                <th>Returns</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->truck }}</td>
                    <td>{{ $row->seq }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->drop_address }}</td>
                    <td>{{ $row->route }}</td>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->draw }}</td>
                    <td>{{ $row->returns }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
