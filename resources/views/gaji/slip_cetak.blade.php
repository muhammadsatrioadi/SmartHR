<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slip Gaji - {{ $karyawan->name }} - {{ $slip['label_periode'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 1.5rem; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a class="btn btn-outline-secondary btn-sm" href="{{ url()->previous() }}">{{ __("Kembali") }}</a>
            <button class="btn btn-primary btn-sm" onclick="window.print()">{{ __("Cetak / Simpan PDF") }}</button>
        </div>

        @include('partials.slip_gaji', ['slip' => $slip, 'karyawan' => $karyawan])
    </div>
</body>
</html>
