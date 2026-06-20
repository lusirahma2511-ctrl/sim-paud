<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penilaian Perkembangan Anak</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
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

        @media print {
            .float-buttons { display: none !important; }
        }

        body{
            font-family: 'Times New Roman', Times, serif;
            font-size:12px;
            color:#333;
            padding:25px;
        }

        /* =========================
           HEADER
        ========================= */

        .kop{
            width:100%;
            border-bottom:3px solid #000;
            padding-bottom:15px;
            margin-bottom:20px;
        }

        .kop td{
            vertical-align:middle;
        }

        .logo{
            width:90px;
        }

        .judul{
            text-align:center;
        }

        .judul h2{
            font-size:22px;
            margin-bottom:5px;
            text-transform:uppercase;
        }

        .judul h3{
            font-size:16px;
            margin-bottom:5px;
        }

        .judul p{
            font-size:12px;
            color:#555;
        }

        /* =========================
           INFO
        ========================= */

        .info{
            width:100%;
            margin-bottom:20px;
        }

        .info td{
            padding:4px 0;
            font-size:13px;
        }

        /* =========================
           SECTION TITLE
        ========================= */

        .section-title{
            margin-top:25px;
            margin-bottom:10px;
            padding:8px 12px;
            background:#4e73df;
            color:white;
            font-weight:bold;
            border-radius:5px;
        }

        /* =========================
           STATISTIK
        ========================= */

        .statistik{
            width:100%;
            margin-bottom:15px;
        }

        .statistik td{
            width:25%;
            padding:10px;
        }

        .box{
            border-radius:8px;
            padding:15px;
            text-align:center;
            color:white;
            font-weight:bold;
        }

        .bb{ background:#e74c3c; }
        .mb{ background:#f39c12; }
        .bsh{ background:#3498db; }
        .bsb{ background:#2ecc71; }

        .box h2{
            font-size:28px;
            margin-bottom:5px;
        }

        /* =========================
           TABLE
        ========================= */

        table{
            width:100%;
            border-collapse:collapse;
        }

        .table th{
            background:#4e73df;
            color:white;
            border:1px solid #ddd;
            padding:10px;
            font-size:12px;
        }

        .table td{
            border:1px solid #ddd;
            padding:9px;
            text-align:center;
            font-size:12px;
        }

        .text-left{
            text-align:left;
        }

        tbody tr:nth-child(even){
            background:#f9fbff;
        }

        /* =========================
           BADGE
        ========================= */

        .badge{
            padding:5px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:bold;
            display:inline-block;
        }

        .badge-bb{
            background:#fde2e2;
            color:#c0392b;
        }

        .badge-mb{
            background:#fff4d6;
            color:#b9770e;
        }

        .badge-bsh{
            background:#d6eaff;
            color:#2166c2;
        }

        .badge-bsb{
            background:#d8f8e3;
            color:#1e8449;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer{
            width:100%;
            margin-top:50px;
        }

        .footer td{
            text-align:center;
            padding-top:60px;
            font-size:13px;
        }

        /* =========================
           PRINT
        ========================= */

        @media print{

            body{
                padding:10px;
            }

        }

    </style>
</head>

<body>

@if(!isset($pdf))
<!-- FLOAT BUTTONS (NO PRINT) -->
<div class="float-buttons">
    <a href="{{ route('admin.laporan.penilaian') }}" class="btn-action btn-back">
        ← Kembali
    </a>
    <button onclick="window.print()" class="btn-action btn-print">
        🖨 Print
    </button>
</div>
@endif

<!-- =========================
     HEADER SEKOLAH
========================= -->

<table class="kop">

    <tr>

        <td width="90">

            <img src="{{ public_path('logo.png') }}"
                 class="logo">

        </td>

        <td class="judul">
            <h2>POS PAUD TERATAI SINDANGSARI</h2>
            <h3>Laporan Penilaian Perkembangan Anak</h3>
            <p>
                Tahun Ajaran {{ $tahunAjaran ?? date('Y') }}
            </p>
            @if(isset($semester) && $semester)
                <p class="mt-1">
                    Semester {{ $semester }}
                </p>
            @endif
            @if(isset($kelas_id) && $kelas_id)
                @php
                    $selectedKelas = \App\Models\Kelas::find($kelas_id);
                @endphp
                @if($selectedKelas)
                    <p class="mt-1">
                        Kelas {{ $selectedKelas->nama_kelas }}
                    </p>
                @endif
            @endif
        </td>

    </tr>

</table>

<!-- =========================
     INFO
========================= -->

<table class="info">

    <tr>

        <td width="180">
            Tanggal Cetak
        </td>

        <td width="10">
            :
        </td>

        <td>
            {{ date('d F Y') }}
        </td>

    </tr>

    <tr>

        <td>
            Total Siswa
        </td>

        <td>
            :
        </td>

        <td>
            {{ count($data) }} Anak
        </td>

    </tr>

</table>

<!-- =========================
     STATISTIK
========================= -->

<div class="section-title">
    Statistik Penilaian Anak
</div>

<table class="statistik">

    <tr>

        <td>

            <div class="box bb">

                <h2>{{ $jumlahBB ?? 0 }}</h2>

                BB

            </div>

        </td>

        <td>

            <div class="box mb">

                <h2>{{ $jumlahMB ?? 0 }}</h2>

                MB

            </div>

        </td>

        <td>

            <div class="box bsh">

                <h2>{{ $jumlahBSH ?? 0 }}</h2>

                BSH

            </div>

        </td>

        <td>

            <div class="box bsb">

                <h2>{{ $jumlahBSB ?? 0 }}</h2>

                BSB

            </div>

        </td>

    </tr>

</table>

<!-- =========================
     REKAP PER KELAS
========================= -->

<div class="section-title">
    Rekap Per Kelas
</div>

<table class="table">

    <thead>

        <tr>

            <th>Kelas</th>
            <th>Jumlah Anak</th>
            <th>BSB</th>
            <th>BSH</th>
            <th>MB</th>
            <th>BB</th>

        </tr>

    </thead>

    <tbody>

        @foreach($rekapKelas as $r)

        <tr>

            <td>{{ $r['kelas'] }}</td>
            <td>{{ $r['jumlah'] }}</td>
            <td>{{ $r['bsb'] }}</td>
            <td>{{ $r['bsh'] }}</td>
            <td>{{ $r['mb'] }}</td>
            <td>{{ $r['bb'] }}</td>

        </tr>

        @endforeach

    </tbody>

</table>

<!-- =========================
     TABEL PENILAIAN
========================= -->

<div class="section-title">
    Detail Penilaian Seluruh Anak
</div>

<table class="table">

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="20%">Nama Anak</th>

            @foreach($kriteriaList as $kriteria)

                <th>{{ $kriteria }}</th>

            @endforeach

        </tr>

    </thead>

    <tbody>

    @forelse($data as $d)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td class="text-left">

                <strong>{{ $d['nama'] }}</strong>

            </td>

            @foreach($kriteriaList as $kriteria)

                @php

                    $nilai = $d[$kriteria] ?? '-';

                    $badge = 'badge-bb';

                    if($nilai == 'BSB'){
                        $badge = 'badge-bsb';
                    }elseif($nilai == 'BSH'){
                        $badge = 'badge-bsh';
                    }elseif($nilai == 'MB'){
                        $badge = 'badge-mb';
                    }

                @endphp

                <td>

                    <span class="badge {{ $badge }}">
                        {{ $nilai }}
                    </span>

                </td>

            @endforeach

        </tr>

    @empty

        <tr>

            <td colspan="20">
                Tidak ada data penilaian
            </td>

        </tr>

    @endforelse

    </tbody>

</table>

<!-- =========================
     KESIMPULAN
========================= -->

<div class="section-title">
    Kesimpulan
</div>

<p style="margin-top:10px; line-height:1.8; text-align:justify;">

Berdasarkan hasil penilaian perkembangan anak,
mayoritas peserta didik menunjukkan perkembangan
yang baik pada aspek pembelajaran dan perkembangan anak usia dini.
Laporan ini digunakan sebagai bahan evaluasi sekolah
dan monitoring perkembangan peserta didik oleh kepala sekolah.

</p>

<!-- =========================
     TANDA TANGAN
========================= -->

<table class="footer">

    <tr>

        <td width="60%"></td>

        <td>

            Mengetahui,<br>
            Kepala Sekolah

            <br><br><br><br>

            _______________________

        </td>

    </tr>

</table>

<script>
    window.print();
</script>

</body>
</html>