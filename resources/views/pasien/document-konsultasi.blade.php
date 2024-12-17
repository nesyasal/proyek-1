<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Konsultasi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Detail Konsultasi</h1>
    <table>
        <tr>
            <th>Nama Pasien</th>
            <td>{{ $consultation->pasien_name }}</td>
        </tr>
        <tr>
            <th>Nama Dokter</th>
            <td>{{ $consultation->doctor_name }}</td>
        </tr>
        <tr>
            <th>Tanggal Konsultasi</th>
            <td>{{ $consultation->tanggal_konsultasi }}</td>
        </tr>
        <tr>
            <th>Keluhan Pasien</th>
            <td>{{ $consultation->keluhan_pasien }}</td>
        </tr>
        <tr>
            <th>Balasan Dokter</th>
            <td>{{ $consultation->balasan_dokter }}</td>
        </tr>
    </table>
</body>
</html>