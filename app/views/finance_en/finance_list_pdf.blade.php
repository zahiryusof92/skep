<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finance List</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 8px; margin: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background: #eee; font-weight: bold; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <h2 style="margin-bottom: 10px;">Finance List</h2>
    <p style="margin-bottom: 10px;">Generated: {{ date('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                @foreach ($headers as $h)
                <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{ $row['cob'] }}</td>
                <td>{{ $row['file_no'] }}</td>
                <td>{{ $row['strata'] }}</td>
                <td>{{ $row['month'] }}</td>
                <td>{{ $row['year'] }}</td>
                <td>{{ $row['status'] }}</td>
                <td>{{ $row['created_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
