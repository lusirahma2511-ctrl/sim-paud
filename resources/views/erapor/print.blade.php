<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Laporan Perkembangan Anak Didik - POS PAUD Teratai</title>
    <style>
        @page {
            size: A4;
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
            padding: 12px 20px;
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Times New Roman', Times, serif !important;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .page {
            font-family: 'Times New Roman', Times, serif !important;
            margin:20px auto;
            padding: 20px;
            background: white;
            margin: 0 auto;
           /* Equal margin inside the page */
            position: relative;
            page-break-after: always;
           max-width: 210mm;
            max-height: 297mm; /* Exact A4 height */
          
        }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            .page {
                margin: 0;
                box-shadow: none;
                width: 210mm;
                height: 297mm;
            }
            .no-print, .float-buttons { display: none !important; }
        }

        /* Centering for Cover */
        .cover-table {
            width: 100%;
            height: 100%; /* Fill the entire page minus padding */
            border: none;
            border-collapse: collapse;
        }
        .cover-table td {
            text-align: center;
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .cover-logo {
            width: 120px;
            display: block;
            margin: 10px auto;
        }
        .cover-illustration {
            width: 350px;
            display: block;
            margin: 30px auto;
        }
        
        /* Helpers */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .full-width { width: 100%; }

        /* Section Titles */
        .section-title-box {
            background-color: #e8e4f1 !important;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }
        .section-bar {
            background-color: #e8e4f1 !important;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 12pt;
            margin: 20px 0 10px 0;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table th, .table td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 12pt;
        }
        .table th {
            background-color: #6f42c1 !important;
            color: white !important;
            text-align: center;
        }

        /* Data Layout */
        .data-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        .data-table td {
            border: none;
            padding: 5px 2px;
            font-size: 12pt;
            vertical-align: top;
        }
        .label-col { width: 220px; }
        .colon-col { width: 20px; text-align: center; }

        /* Signature & Footer */
        .footer-table {
            width: 100%;
            margin-top: 40px;
            border: none;
        }
        .footer-table td {
            border: none;
            text-align: center;
            width: 33.33%;
            vertical-align: bottom;
            font-size: 11pt;
        }
        .photo-box {
            border: 1px solid #70ad47;
            width: 100px;
            height: 130px;
            display: block;
            margin: 0 auto;
            text-align: center;
            line-height: 130px;
            font-size: 10pt;
        }

        /* Chart Fix for DomPDF */
        .chart-box {
            width: 100%;
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 20px;
            text-align: center; /* Center the image */
        }
        /* New Bar Chart Styles */
        .bar-chart-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
            margin-top: 20px;
        }
        .bar-chart-row {
            display: table; /* Use table for DomPDF compatibility */
            width: 100%;
            margin-bottom: 8px;
        }
        .bar-chart-label {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
            padding-right: 10px;
            font-size: 10pt;
            font-weight: bold;
            text-align: right;
        }
        .bar-chart-bar-wrapper {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }
        .bar-chart-bar {
            height: 20px;
            background-color: #4CAF50 !important; /* Default green */
            text-align: right;
            color: white !important;
            line-height: 20px;
            padding-right: 5px;
            font-size: 9pt;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
        }
        .bar-chart-legend {
            display: table;
            width: 100%;
            margin-top: 20px;
            font-size: 10pt;
        }
        .bar-chart-legend-item {
            display: table-cell;
            padding: 5px;
            vertical-align: top;
        }
        .bar-chart-legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
            border: 1px solid #ccc;
        }
        .bar-chart-legend-text {
            vertical-align: middle;
        }

        /* Specific bar colors based on value ranges */
        .bar-red { background-color: #f44336 !important; } /* Perlu Stimulasi */
        .bar-orange { background-color: #ff9800 !important; } /* Mulai Berkembang */
        .bar-yellow { background-color: #ffeb3b !important; color: #333 !important; } /* Berkembang */
        .bar-green { background-color: #4CAF50 !important; } /* Sangat Baik */
        /* Remove chart-row, chart-label, chart-bar-wrapper, chart-bar styles */
        /* as they are no longer needed for the image-based chart */
        .bar-blue { background-color: #4472c4 !important; }
        .bar-orange { background-color: #ed7d31 !important; }
        .bar-grey { background-color: #a5a5a5 !important; }

        .content-box {
            background-color: #e8e4f1 !important;
            border: 1px solid #70ad47;
            padding: 15px;
            margin-bottom: 15px;
            min-height: 80px;
        }
    </style>
</head>
<body>

@if(!isset($pdf))
<!-- FLOAT BUTTONS (NO PRINT) -->
<div class="float-buttons">
    <a href="{{ route('admin.erapor.index') }}" class="btn-action btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <button onclick="window.print()" class="btn-action btn-print">
        <i class="fas fa-print"></i> Cetak Sekarang
    </button>
</div>
@endif

<!-- ================= HALAMAN 1 (Cover) ================= -->
<div class="page" style="background-color: #e8e4f1 !important;">
    <table class="cover-table">
        <tr>
            <td>
                @if(isset($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo PAUD" class="cover-logo">
                @else
                    <img src="{{ asset('images/logo paud rmv.png') }}" alt="Logo PAUD" class="cover-logo">
                @endif

                <div style="margin-top: 50px;">
                    <h1 style="font-weight: bold; font-size: 22pt; margin-bottom: 5px;">LAPORAN PENILAIAN</h1>
                    <h1 style="font-weight: bold; font-size: 22pt;">PERKEMBANGAN ANAK DIDIK</h1>
                </div>

                <div style="margin-top: 50px;">
                    @if(isset($fotoIlustrasiBase64))
                        <img src="{{ $fotoIlustrasiBase64 }}" alt="Foto Anak" class="cover-illustration">
                    @else
                        <img src="{{ asset('images/foto anak.png') }}" alt="Foto Anak" class="cover-illustration">
                    @endif
                </div>

                <div style="margin-top: 40px;">
                    <div style="font-size: 14pt; margin-bottom: 5px; font-weight: bold;">NAMA SISWA</div>
                    <div style="font-size: 18pt; font-weight: bold; margin-bottom: 10px;">{{ strtoupper($siswa->nama_siswa) }}</div>
                    <div style="font-size: 14pt; font-weight: bold;">NISN : {{ $siswa->nisn }}</div>
                </div>

                <div style="margin-top: 80px; font-size: 12pt; line-height: 1.5;">
                    <div style="font-weight: bold;">POS PAUD TERATAI SINDANGSARI</div>
                    <div>Kp. Sindangsari RT 002 RW 021 Desa Ciwidey Kec. Ciwidey</div>
                    <div>Kabupaten Bandung - Jawa Barat 40973</div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ================= HALAMAN 2 (Identitas) ================= -->
<div class="page">
    <div class="section-title-box">
        <h2 style="font-weight: bold; font-size: 18pt;">IDENTITAS SISWA</h2>
    </div>

    <div class="section-bar">A. DATA SISWA</div>
    <table class="data-table" style="margin-left: 15px;">
        <tr><td class="label-col">1. Nama Lengkap</td><td class="colon-col">:</td><td>{{ strtoupper($siswa->nama_siswa) }}</td></tr>
        <tr><td class="label-col">2. Nama Panggilan</td><td class="colon-col">:</td><td>{{ strtoupper($siswa->nama_panggilan ?? '-') }}</td></tr>
        <tr><td class="label-col">3. NISN</td><td class="colon-col">:</td><td>{{ $siswa->nisn }}</td></tr>
        <tr><td class="label-col">4. Tempat, Tanggal Lahir</td><td class="colon-col">:</td><td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="label-col">5. Jenis Kelamin</td><td class="colon-col">:</td><td>{{ $siswa->jk == 1 ? 'Laki-Laki' : 'Perempuan' }}</td></tr>
        <tr><td class="label-col">6. Agama</td><td class="colon-col">:</td><td>{{ $siswa->agama ?? '-' }}</td></tr>
        <tr><td class="label-col">7. Anak Ke-</td><td class="colon-col">:</td><td>{{ $siswa->anak_ke ?? '-' }}</td></tr>
        <tr><td class="label-col">8. Jumlah Saudara</td><td class="colon-col">:</td><td>{{ $siswa->jumlah_saudara ?? '-' }}</td></tr>
        <tr><td class="label-col">9. Alamat Rumah</td><td class="colon-col">:</td><td>{{ $siswa->alamat }}</td></tr>
    </table>

    <div class="section-bar" style="margin-top: 30px;">B. DATA ORANG TUA/WALI</div>
    <table class="data-table" style="margin-left: 15px;">
        <tr><td class="label-col">1. Nama Ayah</td><td class="colon-col">:</td><td>{{ strtoupper($siswa->orangTua->nama_ayah ?? '-') }}</td></tr>
        <tr><td class="label-col">2. Nama Ibu</td><td class="colon-col">:</td><td>{{ strtoupper($siswa->orangTua->nama_ibu ?? '-') }}</td></tr>
        <tr><td class="label-col">3. Pekerjaan Ayah</td><td class="colon-col">:</td><td>{{ $siswa->orangTua->pekerjaan_ayah ?? '-' }}</td></tr>
        <tr><td class="label-col">4. Pekerjaan Ibu</td><td class="colon-col">:</td><td>{{ $siswa->orangTua->pekerjaan_ibu ?? '-' }}</td></tr>
        <tr><td class="label-col">5. Nama Wali</td><td class="colon-col">:</td><td>{{ $siswa->orangTua->nama_wali ?? '-' }}</td></tr>
        <tr><td class="label-col">6. No Telepon Orang Tua</td><td class="colon-col">:</td><td>{{ $siswa->orangTua->no_hp ?? '-' }}</td></tr>
        <tr><td class="label-col">7. Alamat Orang Tua</td><td class="colon-col">:</td><td>{{ $siswa->orangTua->alamat ?? $siswa->alamat }}</td></tr>
    </table>

    <table class="footer-table" style="margin-top: 100px;">
        <tr>
            <td style="text-align: left; padding-left: 50px;">
                <div class="photo-box">
                    @if(isset($fotoSiswaBase64))
                        <img src="{{ $fotoSiswaBase64 }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        Foto 3x4
                    @endif
                </div>
            </td>
            <td></td>
            <td style="text-align: center; padding-right: 50px;">
                Ciwidey, {{ date('d/m/Y') }}<br>
                Kepala Sekolah<br><br><br><br><br>
                <strong>{{ $kepalaSekolah->nama_guru ?? '....................' }}</strong><br>
                @if(isset($kepalaSekolah->nip)) NIP. {{ $kepalaSekolah->nip }} @endif
            </td>
        </tr>
    </table>
</div>

<!-- ================= HALAMAN 3+ (Hasil per Semester) ================= -->
@foreach($semesterData as $sem)
<div class="page">
    <div class="section-title-box">
        <h2 style="font-weight: bold; font-size: 18pt;">HASIL PENILAIAN PERKEMBANGAN ANAK - SEMESTER {{ $sem['semester'] }}</h2>
    </div>

    <table class="data-table" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%;">
                <table class="data-table">
                    <tr><td style="width: 100px;">Nama Siswa</td><td>:</td><td style="font-weight: bold;">{{ strtoupper($siswa->nama_siswa) }}</td></tr>
                    <tr><td>NISN</td><td>:</td><td>{{ $siswa->nisn }}</td></tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table class="data-table">
                    <tr><td style="width: 100px;">Kelas</td><td>:</td><td>{{ $siswa->kelas->nama_kelas }}</td></tr>
                    <tr><td>Semester</td><td>:</td><td>{{ $sem['semester'] }} (2025/2026)</td></tr>
                </table>
            </td>
        </tr>
    </table>

<!-- TABEL CAPAIAN (Versi Standar Kurikulum Merdeka) -->
<table class="table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="30%">Dimensi Perkembangan</th>
            <th width="35%">Indikator / Pengertian</th>
            <th width="10%">Hasil</th> <!-- Singkatan (SB/BSH/MB) -->
            <th width="20%">Keterangan</th> <!-- Teks Lengkap -->
        </tr>
    </thead>
    <tbody>
@foreach($sem['kriterias'] as $i => $k)
    @php
        $dataNilai = $sem['nilaiArray'][$k->id] ?? null;
        $teksAsli = is_array($dataNilai) ? $dataNilai['teks'] : 'Belum Teramati';
        
        // 1. Tentukan Singkatan
        $singkatan = '-';
        if (stripos($teksAsli, 'Sangat Baik') !== false) $singkatan = 'SB';
        elseif (stripos($teksAsli, 'Sesuai Harapan') !== false) $singkatan = 'BSH';
        elseif (stripos($teksAsli, 'Mulai Berkembang') !== false) $singkatan = 'MB';
        elseif (stripos($teksAsli, 'Belum Berkembang') !== false) $singkatan = 'BB';

        // 2. Hilangkan bagian dalam kurung untuk kolom Keterangan
        // Regex ini akan menghapus spasi dan teks di dalam kurung, misal "Sangat Baik (SB)" -> "Sangat Baik"
        $teksBersih = trim(preg_replace('/\s*\([^)]*\)/', '', $teksAsli));
    @endphp
    <tr>
        <td class="text-center">{{ $i + 1 }}</td>
        <td class="text-bold">{{ $k->nama_kriteria }}</td>
        <td style="font-size: 9pt; color: #555;">{{ $k->deskripsi }}</td>
        <td class="text-center">
            <span style="font-weight: bold; font-size: 14pt; color: #6f42c1;">{{ $singkatan }}</span>
        </td>
        <td class="text-center" style="font-size: 11pt;">
            {{ $teksBersih }}
        </td>
    </tr>
@endforeach
    </tbody>
</table>

<!-- BAGIAN SAW (DISAMARKAN MENJADI NARASI DETAIL) -->
<div class="section-bar">KESIMPULAN PERKEMBANGAN</div>
<div class="content-box" style="min-height: 120px; line-height: 1.6;">
    @php
        $skor = $sem['sawResult']['total'];
        
        if($skor >= 0.85) {
            $narasi = "Ananda menunjukkan kualitas perkembangan yang <strong>Sangat Optimal</strong>. Sebagian besar dimensi profil lulusan telah tercapai dengan sangat baik, terutama dalam hal kemandirian dan interaksi sosial. Ananda mampu menjadi teladan bagi teman sebaya dan menunjukkan rasa ingin tahu yang besar terhadap hal-hal baru di lingkungan sekolah.";
        } 
        elseif($skor >= 0.70) {
            $narasi = "Ananda menunjukkan perkembangan yang <strong>Baik dan Konsisten</strong> sesuai dengan tahap usianya. Ananda mampu mengikuti kegiatan pembelajaran dengan antusias, mulai menunjukkan sikap kolaboratif dalam kelompok, serta memahami nilai-nilai dasar budi pekerti yang diajarkan. Dimensi kesehatan dan komunikasi berkembang dengan sangat stabil.";
        }
        elseif($skor >= 0.55) {
            $narasi = "Ananda menunjukkan proses perkembangan yang <strong>Cukup Baik</strong>. Beberapa dimensi sudah mulai muncul dan berkembang, namun masih memerlukan bimbingan serta stimulasi yang lebih intensif pada beberapa aspek tertentu. Dengan pendampingan yang tepat, ananda akan terus berproses menuju capaian perkembangan yang diharapkan.";
        }
        else {
            $narasi = "Ananda sedang berada dalam <strong>Tahap Awal Perkembangan</strong>. Saat ini ananda sangat membutuhkan dukungan, kesabaran, dan stimulasi yang berkelanjutan baik di sekolah maupun di rumah agar setiap dimensi profil lulusan dapat muncul dan berkembang lebih optimal di masa mendatang.";
        }
    @endphp
    
    <!-- PERUBAHAN DI SINI: Gunakan tanda seru agar HTML terbaca -->
    <p style="text-align: justify;">{!! $narasi !!}</p>
    
    <p style="margin-top: 15px; font-size: 9pt; color: #555; font-style: italic; border-top: 1px solid #ccc; padding-top: 5px;">
        *Kesimpulan ini merupakan hasil analisis otomatis sistem digital berdasarkan integrasi 8 dimensi perkembangan anak didik selama satu semester.
    </p>
</div>


</div>

<!-- ================= HALAMAN 4+ (Grafik per Semester) ================= -->
<div class="page">
    <div class="text-center text-bold" style="font-size: 14pt; margin-bottom: 20px;">CATATAN PERKEMBANGAN ANAK - SEMESTER {{ $sem['semester'] }}</div>
    
    <div class="content-box">
        @php
            // Mencari dimensi dengan nilai tertinggi
            $idTertinggi = collect($sem['nilaiArray'])->sortByDesc('angka')->keys()->first();
            $dimensiUnggul = $sem['kriterias']->where('id', $idTertinggi)->first()->nama_kriteria ?? 'Kemandirian';
            
            // Mencari dimensi dengan nilai terendah untuk saran
            $idTerendah = collect($sem['nilaiArray'])->sortBy('angka')->keys()->first();
            $dimensiRendah = $sem['kriterias']->where('id', $idTerendah)->first()->nama_kriteria ?? 'Kedisiplinan';
        @endphp

        <div class="text-bold">Deskripsi Perkembangan:</div>
        <p style="font-size: 11pt; margin-top: 5px; line-height: 1.5; text-align: justify;">
            @if($siswa->catatan_perkembangan)
                {{ $siswa->catatan_perkembangan }}
            @else
                @if($skor >= 0.85)
                    Selama kurun waktu satu semester ini, Ananda telah menunjukkan dedikasi dan semangat belajar yang luar biasa. Secara menyeluruh, profil perkembangan Ananda dikategorikan sebagai pencapaian yang <strong>Sangat Optimal dan Memuaskan</strong>. Ananda tidak hanya mampu beradaptasi dengan sangat baik, namun juga secara konsisten memperlihatkan rasa ingin tahu yang tinggi serta memiliki daya imajinasi yang kreatif dalam setiap proses bermain sambil belajar.
                @elseif($skor >= 0.70)
                    Dalam kurun waktu semester ini, Ananda menunjukkan progres yang <strong>Baik dan Terus Berkembang</strong>. Ananda sudah mampu mengikuti ritme pembelajaran dengan antusias dan menunjukkan kemandirian yang stabil. Kemampuan adaptasi sosialnya sangat baik, terlihat dari cara Ananda berinteraksi secara aktif dengan teman dan guru di berbagai kegiatan kelas.
                @elseif($skor >= 0.50)
                    Sepanjang semester ini, Ananda menunjukkan perkembangan yang <strong>Cukup Baik dan Cukup Stabil</strong>. Ananda mulai menunjukkan minat pada kegiatan-kegiatan tertentu dan sudah mampu mengikuti arahan guru meskipun terkadang masih memerlukan dorongan atau stimulasi tambahan untuk tetap fokus. Secara umum, Ananda berada dalam proses transisi yang positif menuju kemandirian.
                @else
                    Selama semester ini, Ananda berada dalam tahap <strong>Awal Perkembangan</strong> yang memerlukan perhatian khusus. Ananda sedang dalam proses beradaptasi dengan lingkungan sekolah dan rutinitas harian. Meskipun masih memerlukan pendampingan intensif dalam banyak aspek, Ananda mulai menunjukkan usaha untuk terlibat dalam interaksi sederhana dengan teman-temannya.
                @endif
            @endif
        </p>
        
        <div class="text-bold" style="margin-top: 15px;">Uraian Perkembangan:</div>
        <p style="font-size: 11pt; margin-top: 5px; line-height: 1.5; text-align: justify;">
            @if($siswa->uraian_perkembangan)
                {{ $siswa->uraian_perkembangan }}
            @else
                Tinjauan mendalam terhadap pencapaian belajar menunjukkan bahwa kemajuan paling signifikan terpantau pada dimensi <strong>{{ $dimensiUnggul }}</strong>. 
                @if($skor >= 0.85)
                    Dalam aspek ini, Ananda berhasil membuktikan kemampuannya untuk memimpin dan menjadi teladan bagi teman sebaya, serta menunjukkan inisiatif yang sangat mandiri.
                @elseif($skor >= 0.70)
                    Dalam aspek ini, Ananda berhasil membuktikan kemampuannya untuk mengikuti instruksi dengan saksama sekaligus mulai menunjukkan inisiatif yang baik dalam dinamika kelompok.
                @else
                    Dalam aspek ini, Ananda mulai memperlihatkan ketertarikan dan usaha untuk mengikuti kegiatan kelas dengan lebih konsisten dibandingkan awal semester.
                @endif
            @endif
        </p>
    </div>

    <div class="text-center text-bold" style="font-size: 14pt; margin-bottom: 10px; margin-top: 30px;">SARAN UNTUK ORANG TUA</div>
    <div class="content-box" style="min-height: 100px;">
        <p style="font-size: 11pt; line-height: 1.6; text-align: justify;">
            @if($siswa->saran_orangtua)
                {{ $siswa->saran_orangtua }}
            @else
                @if($skor >= 0.85)
                    Untuk menjaga kesinambungan progres yang sangat memuaskan ini, kami menyarankan agar orang tua terus memberikan tantangan baru yang edukatif bagi Ananda di rumah. Tetap berikan apresiasi atas setiap pencapaiannya agar rasa percaya diri Ananda tetap terjaga dan semakin matang sebagai bekal ke jenjang berikutnya.
                @elseif($skor >= 0.70)
                    Untuk menjaga kesinambungan progres perkembangan yang sudah dicapai, kami mengharapkan peran aktif orang tua untuk terus memberikan pendampingan di rumah, khususnya dalam menstimulasi dimensi <strong>{{ $dimensiRendah }}</strong>. Memberikan pujian yang tulus atas setiap usaha kecil Ananda akan sangat membantu meningkatkan motivasi belajarnya.
                @elseif($skor >= 0.50)
                    Kami sangat mengharapkan kerja sama orang tua untuk lebih sering mengajak Ananda berdialog dan melatih fokus melalui kegiatan bermain di rumah. Stimulasi rutin pada aspek <strong>{{ $dimensiRendah }}</strong> akan sangat membantu Ananda mencapai tingkat perkembangan yang lebih optimal pada semester mendatang.
                @else
                    Perhatian dan pendampingan yang lebih intensif dari orang tua di rumah sangat diperlukan untuk membantu proses adaptasi Ananda. Kami menyarankan agar orang tua menciptakan rutinitas yang stabil dan memberikan banyak stimulasi motorik maupun komunikasi sederhana, khususnya pada aspek <strong>{{ $dimensiRendah }}</strong>, guna membangun rasa aman dan percaya diri Ananda.
                @endif
            @endif
        </p>
    </div>

    <div class="text-center text-bold" style="font-size: 14pt; margin-top: 40px; margin-bottom: 20px;">GRAFIK ANALISIS PERKEMBANGAN</div>
    
    <div class="bar-chart-container" style="border: 1px solid #ddd; border-radius: 8px;">
        @foreach($sem['kriterias'] as $k)
            @php
                $dataNilai = $sem['nilaiArray'][$k->id] ?? null;
                $nilaiAngka = is_array($dataNilai) ? $dataNilai['angka'] : 0;
                
                $barColorClass = 'bar-red'; 
                if ($nilaiAngka >= 85) {
                    $barColorClass = 'bar-green';
                } elseif ($nilaiAngka >= 75) {
                    $barColorClass = 'bar-blue'; 
                } elseif ($nilaiAngka >= 60) {
                    $barColorClass = 'bar-orange';
                }
            @endphp
            <div class="bar-chart-row" style="margin-bottom: 12px;">
                <div class="bar-chart-label" style="font-size: 9pt;">{{ $k->nama_kriteria }}</div>
                <div class="bar-chart-bar-wrapper">
                    <div class="bar-chart-bar {{ $barColorClass }}" style="width: {{ $nilaiAngka }}%; min-width: 3%; height: 18px;">
                        &nbsp;
                    </div>
                </div>
            </div>
        @endforeach

        <div class="bar-chart-legend" style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 10px;">
            <div class="bar-chart-legend-item">
                <span class="bar-chart-legend-color bar-green"></span>
                <span class="bar-chart-legend-text">Sangat Baik</span>
            </div>
            <div class="bar-chart-legend-item">
                <span class="bar-chart-legend-color bar-blue"></span>
                <span class="bar-chart-legend-text">Sesuai Harapan</span>
            </div>
            <div class="bar-chart-legend-item">
                <span class="bar-chart-legend-color bar-orange"></span>
                <span class="bar-chart-legend-text">Mulai Berkembang</span>
            </div>
            <div class="bar-chart-legend-item">
                <span class="bar-chart-legend-color bar-red"></span>
                <span class="bar-chart-legend-text">Perlu Stimulasi</span>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ================= HALAMAN TERAKHIR (Presensi & Pengesahan) ================= -->
<div class="page">
    <div class="section-bar" style="text-align: center;">KEHADIRAN & PENGESAHAN</div>
    
    <table style="width: 100%; margin-top: 30px; border-collapse: collapse;">
        <tr>
            <!-- TABEL PRESENSI -->
            <td style="width: 250px; vertical-align: top;">
                <table class="table" style="margin-top: 0;">
                    <tr><th colspan="2" style="background: #6f42c1 !important; color: white !important;">Ketidakhadiran</th></tr>
                    <tr><td>Sakit</td><td class="text-center">{{ $presensi['sakit'] ?? 0 }} Hari</td></tr>
                    <tr><td>Izin</td><td class="text-center">{{ $presensi['izin'] ?? 0 }} Hari</td></tr>
                    <tr><td>Tanpa Ket.</td><td class="text-center">{{ $presensi['alpha'] ?? 0 }} Hari</td></tr>
                </table>
            </td>
            
            <td style="width: 50px;"></td>

            <!-- KOTAK KETERANGAN -->
            <td style="vertical-align: top;">
                <div style="border: 1px solid #70ad47; background-color: #e8e4f1 !important; padding: 20px; text-align: center; border-radius: 10px;">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #6f42c1;">Status Capaian Akhir</div>
                    <div style="font-size: 16pt; font-weight: bold; margin-bottom: 5px; color: #2c3e50;">
                        @php
                            $lastSem = end($semesterData);
                            $lastSkor = $lastSem['sawResult']['total'];
                        @endphp
                        @if($lastSkor >= 0.85)
                            Sangat Berkembang
                        @elseif($lastSkor >= 0.70)
                            Berkembang Sesuai Harapan
                        @else
                            Teruslah Berlatih
                        @endif
                    </div>
                    <div style="font-size: 10pt; font-style: italic;">"Setiap anak adalah bintang yang bersinar dengan caranya sendiri."</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="footer-table" style="margin-top: 100px;">
        <tr>
            <td>Orang Tua/Wali<br><br><br><br><br><strong>{{ $siswa->orangTua->nama_ibu ?? '....................' }}</strong></td>
            <td>Kepala Sekolah<br><br><br><br><br><strong>{{ $kepalaSekolah->nama_guru ?? '....................' }}</strong><br>@if(isset($kepalaSekolah->nip)) NIP. {{ $kepalaSekolah->nip }} @endif</td>
            <td>Ciwidey, {{ date('d/m/Y') }}<br>Guru Kelas<br><br><br><br><br><strong>{{ $guruKelas->nama_guru ?? '....................' }}</strong></td>
        </tr>
    </table>
</div>

</body>
</html>