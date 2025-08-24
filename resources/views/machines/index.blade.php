<!DOCTYPE html>
<html>
<head>
    <title>Machine Monitor</title>
</head>
<body>
    <h1>Daftar Mesin</h1>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Last Temp</th>
                <th>Last Speed</th>
                <th>Recorded At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machines as $machine)
                <tr>
                    <td>{{ $machine->id }}</td>
                    <td>{{ $machine->name }}</td>
                    <td>{{ $machine->location }}</td>
                    <td>{{ $machine->readings->last()->temperature ?? '-' }}</td>
                    <td>{{ $machine->readings->last()->speed ?? '-' }}</td>
                    <td>{{ $machine->readings->last()->created_at ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
