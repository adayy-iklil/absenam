<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Absensi Siswa - SMK NEGERI 6 JAKARTA</title>
    <!-- Favicon Resmi SMKN 6 Jakarta (Warna Asli 100%) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }
        .header { display: flex; items-center: center; justify-content: center; gap: 15px; border-bottom: 3px double #2563eb; padding-bottom: 12px; margin-bottom: 20px; text-align: center; }
        .header img { width: 60px; height: 60px; object-fit: contain; }
        .header-text h1 { margin: 0; font-size: 18px; color: #2563eb; text-transform: uppercase; font-weight: 900; }
        .header-text h2 { margin: 3px 0 0 0; font-size: 14px; color: #0f172a; font-weight: 800; }
        .header-text p { margin: 2px 0 0 0; font-size: 10px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; }
        .badge-hadir { background: #dcfce7; color: #15803d; }
        .badge-terlambat { background: #fef3c7; color: #b45309; }
        .badge-ditolak { background: #ffe4e6; color: #be123c; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo SMKN 6 Jakarta">
        <div class="header-text">
            <h1>SMK NEGERI 6 JAKARTA</h1>
            <h2>LAPORAN REKAPITULASI ABSENSI DIGITAL SISWA</h2>
            <p>Sistem Absenam Digital | Tanggal Cetak: {{ date('d F Y H:i') }} WIB</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th class="text-center">Status</th>
                <th>Alamat GPS</th>
                <th>Verifikator</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                <td>{{ $row->time }} WIB</td>
                <td>{{ $row->student->nis ?? '-' }}</td>
                <td><strong>{{ $row->student->name ?? ($row->student->user->name ?? '-') }}</strong></td>
                <td>{{ $row->student->classModel->name ?? '-' }}</td>
                <td>{{ $row->student->department->code ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge {{ $row->status == 'Hadir' ? 'badge-hadir' : ($row->status == 'Terlambat' ? 'badge-terlambat' : 'badge-ditolak') }}">
                        {{ $row->status }}
                    </span>
                </td>
                <td>{{ $row->address }}</td>
                <td>{{ $row->teacher->user->name ?? 'System Auto' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
