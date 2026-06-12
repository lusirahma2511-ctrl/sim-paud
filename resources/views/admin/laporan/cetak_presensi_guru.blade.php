{{-- resources/views/admin/laporan/cetak_presensi_guru.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Presensi Guru</title>

    <style>

        body{
            font-family: 'Times New Roman', Times, serif;
            margin:20px;
        }

        /* Float Buttons */
        .float-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 10px 18px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
            font-size: 13px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-print {
            background: #4e73df;
            color: white;
        }

        .btn-action:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        .tanggal{
            text-align:center;
            margin-bottom:20px;
            color:#666;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #333;
            padding:10px;
            text-align:center;
        }

        table th{
            background:#f1f1f1;
        }

        @media print{
            .no-print{
                display:none;
            }
        }

    </style>

</head>
<body>

@if(!isset($pdf))
<!-- FLOAT BUTTONS (NO PRINT) -->
<div class="float-buttons no-print">
    <a href="{{ route('admin.laporan.presensiGuru') }}" class="btn-action btn-back">
        ← Kembali
    </a>
    <button onclick="window.print()" class="btn-action btn-print">
        🖨 Print
    </button>
</div>
@endif

<h2>Laporan Presensi Guru</h2>

<div class="tanggal">

    Bulan
    {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
    {{ $tahun }}

</div>

<table>

    <thead>

    <tr>

        <th>No</th>
        <th>Nama Guru</th>
        <th>Hadir</th>
        <th>Izin</th>
        <th>Sakit</th>
        <th>Alfa</th>

    </tr>

    </thead>

    <tbody>

    @foreach($data as $d)

    <tr>

        <td>{{ $loop->iteration }}</td>
        <td style="text-align:left;">
            {{ $d['nama'] }}
        </td>
        <td>{{ $d['hadir'] }}</td>
        <td>{{ $d['izin'] }}</td>
        <td>{{ $d['sakit'] }}</td>
        <td>{{ $d['alfa'] }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>