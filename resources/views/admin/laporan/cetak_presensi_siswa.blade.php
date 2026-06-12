<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Presensi Siswa</title>

    <meta charset="utf-8">

    <style>
        body{
            font-family: 'Times New Roman', Times, serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #333;
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

        .btn-back-float {
            background: #6c757d;
            color: white;
        }

        .btn-print-float {
            background: #4e73df;
            color: white;
        }

        .btn-action:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        @media print {
            .float-buttons { display: none !important; }
        }

        .container{
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h2{
            text-align:center;
            margin-bottom:5px;
            color:#2c3e50;
        }

        .subtitle{
            text-align:center;
            margin-top:0;
            margin-bottom:25px;
            color:#666;
            font-size:14px;
        }

        .top-action{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .btn-group{
            display:flex;
            gap:10px;
        }

        .btn{
            padding:10px 18px;
            border:none;
            border-radius:8px;
            font-size:14px;
            cursor:pointer;
            text-decoration:none;
            transition:0.2s;
            font-weight:bold;
        }

        .btn-back{
            background:#6c757d;
            color:white;
        }

        .btn-back:hover{
            background:#5a6268;
        }

        .btn-print{
            background:#007bff;
            color:white;
        }

        .btn-print:hover{
            background:#0069d9;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table, th, td{
            border:1px solid #ddd;
        }

        th{
            background:#007bff;
            color:white;
            padding:12px;
            font-size:14px;
        }

        td{
            padding:10px;
            font-size:14px;
            text-align:center;
        }

        td.nama{
            text-align:left;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

        .footer{
            margin-top:30px;
            text-align:right;
            font-size:13px;
            color:#666;
        }

        @media print {

            body{
                background:white;
                padding:0;
            }

            .container{
                box-shadow:none;
                padding:0;
            }

            .no-print{
                display:none !important;
            }

            table{
                margin-top:20px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    @if(!isset($pdf))
    <!-- FLOAT BUTTONS (NO PRINT) -->
    <div class="float-buttons no-print">
        <a href="{{ route('admin.laporan.presensiSiswa') }}" class="btn-action btn-back-float">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn-action btn-print-float">
            🖨 Print
        </button>
    </div>
    @endif

    <!-- TITLE -->
    <h2>Laporan Presensi Siswa</h2>

    <p class="subtitle">
        Bulan
        {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
        {{ $tahun }}
    </p>

    <!-- TABLE -->
    <table>

        <thead>
        <tr>
            <th width="60">No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alfa</th>
        </tr>
        </thead>

        <tbody>

        @forelse($data as $d)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td class="nama">
                {{ $d['nama'] }}
            </td>

            <td>
                {{ $d['kelas'] }}
            </td>

            <td>{{ $d['hadir'] }}</td>

            <td>{{ $d['izin'] }}</td>

            <td>{{ $d['sakit'] }}</td>

            <td>{{ $d['alfa'] }}</td>

        </tr>

        @empty

        <tr>
            <td colspan="7">
                Tidak ada data presensi
            </td>
        </tr>

        @endforelse

        </tbody>

    </table>

    <div class="footer">
        Dicetak pada:
        {{ now()->format('d M Y H:i') }}
    </div>

</div>

</body>
</html>