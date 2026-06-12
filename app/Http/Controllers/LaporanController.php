<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\NilaiPerkembangan;

class LaporanController extends Controller
{
    // ================= DASHBOARD LAPORAN =================
    public function index()
    {
        $bulan = date('m');
        $tahun = date('Y');

        // Hitung persentase kehadiran siswa bulan ini
        $totalSiswa = Siswa::count();
        $hariEfektif = 25; // Asumsi
        $totalHarusnyaSiswa = $totalSiswa * $hariEfektif;
        $totalHadirSiswa = PresensiSiswa::whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->whereIn('status', ['Hadir', 'hadir'])
                            ->count();
        
        $persenSiswa = $totalHarusnyaSiswa > 0 ? round(($totalHadirSiswa / $totalHarusnyaSiswa) * 100) . '%' : '0%';

        // Hitung persentase kehadiran guru bulan ini
        $totalGuru = Guru::count();
        $totalHarusnyaGuru = $totalGuru * $hariEfektif;
        $totalHadirGuru = PresensiGuru::whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->whereIn('status', ['Hadir', 'hadir'])
                            ->count();
        
        $persenGuru = $totalHarusnyaGuru > 0 ? round(($totalHadirGuru / $totalHarusnyaGuru) * 100) . '%' : '0%';

        return view('admin.laporan.index', compact('persenSiswa', 'persenGuru'));
    }

    // =====================================================
    // ================= PRESENSI SISWA =====================
    // =====================================================

    public function presensiSiswa(Request $request)
    {
        $laporan = $this->getDataPresensiSiswa($request);

        return view('admin.laporan.presensi_siswa', $laporan);
    }

    // CETAK
   public function cetakPresensiSiswa(Request $request)
{
    $laporan = $this->getDataPresensiSiswa($request);

    return view('admin.laporan.cetak_presensi_siswa', [
        'data' => $laporan['data'],
        'bulan' => $laporan['bulan'],
        'tahun' => $laporan['tahun']
    ]);
}

    // DOWNLOAD PDF
    public function downloadPresensiSiswa(Request $request)
{
    $laporan = $this->getDataPresensiSiswa($request);

    $pdf = Pdf::loadView('admin.laporan.cetak_presensi_siswa', [
        'data' => $laporan['data'],
        'bulan' => $laporan['bulan'],
        'tahun' => $laporan['tahun'],
        'pdf' => true
    ])->setPaper('a4', 'portrait');

    return $pdf->download('laporan-presensi-siswa.pdf');
}

    // FUNCTION AMBIL DATA PRESENSI SISWA
    private function getDataPresensiSiswa($request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $kelasId = $request->kelas_id;

        $kelas = Kelas::all();

        $siswaQuery = Siswa::with('kelas');

        // FILTER KELAS
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $data = $siswaQuery->get()->map(function ($siswa) use ($bulan, $tahun) {

            $presensi = PresensiSiswa::where('siswa_id', $siswa->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);

            return [
                'nama'   => $siswa->nama_siswa,
                'kelas'  => $siswa->kelas->nama_kelas ?? '-',
                'hadir'  => (clone $presensi)->whereIn('status', ['Hadir', 'hadir'])->count(),
                'izin'   => (clone $presensi)->whereIn('status', ['Izin', 'izin'])->count(),
                'sakit'  => (clone $presensi)->whereIn('status', ['Sakit', 'sakit'])->count(),
                'alfa'   => (clone $presensi)->whereIn('status', ['Alpha', 'alpha', 'Alfa', 'alfa'])->count(),
            ];
        });

        return [
            'data'      => $data,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'kelas'     => $kelas,
            'kelasId'   => $kelasId,
        ];
    }

    // =====================================================
    // ================= PRESENSI GURU ======================
    // =====================================================

    public function presensiGuru(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = Guru::all()->map(function ($guru) use ($bulan, $tahun) {

            $presensi = PresensiGuru::where('guru_id', $guru->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);

            return [
                'nama'  => $guru->nama_guru,
                'hadir' => (clone $presensi)->whereIn('status', ['Hadir', 'hadir'])->count(),
                'izin'  => (clone $presensi)->whereIn('status', ['Izin', 'izin'])->count(),
                'sakit' => (clone $presensi)->whereIn('status', ['Sakit', 'sakit'])->count(),
                'alfa'  => (clone $presensi)->whereIn('status', ['Alpha', 'alpha', 'Alfa', 'alfa'])->count(),
            ];
        });

        return view('admin.laporan.presensi_guru', compact(
            'data',
            'bulan',
            'tahun'
        ));
    }

    public function cetakPresensiGuru(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = Guru::all()->map(function ($guru) use ($bulan, $tahun) {

            $presensi = PresensiGuru::where('guru_id', $guru->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);

            return [
                'nama'  => $guru->nama_guru,
                'hadir' => (clone $presensi)->where('status', 'hadir')->count(),
                'izin'  => (clone $presensi)->where('status', 'izin')->count(),
                'sakit' => (clone $presensi)->where('status', 'sakit')->count(),
                'alfa'  => (clone $presensi)->where('status', 'alpha')->count(),
            ];
        });

        return view('admin.laporan.cetak_presensi_guru', compact(
            'data',
            'bulan',
            'tahun'
        ));
    }

    public function downloadPresensiGuru(Request $request)
{
    $bulan = $request->bulan ?? date('m');
    $tahun = $request->tahun ?? date('Y');

    $data = Guru::all()->map(function ($guru) use ($bulan, $tahun) {

        $presensi = PresensiGuru::where('guru_id', $guru->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        return [
            'nama' => $guru->nama_guru,
            'hadir' => (clone $presensi)->where('status', 'hadir')->count(),
            'izin' => (clone $presensi)->where('status', 'izin')->count(),
            'sakit' => (clone $presensi)->where('status', 'sakit')->count(),
            'alfa' => (clone $presensi)->where('status', 'alpha')->count(),
        ];
    });

    $pdf = Pdf::loadView('admin.laporan.cetak_presensi_guru', [
        'data' => $data,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'pdf' => true
    ]);

    return $pdf->download('laporan-presensi-guru.pdf');
}

    // =====================================================
    // ================= PENILAIAN ==========================
    // =====================================================

public function penilaian()
{
    $rawData = NilaiPerkembangan::with(['siswa', 'kriteria', 'skalaNilai'])->get();

    // Ambil semua kriteria
    $kriteriaList = $rawData
        ->pluck('kriteria.nama_kriteria')
        ->filter()
        ->unique()
        ->values();

    // Group per siswa
    $data = $rawData->groupBy('siswa_id')->map(function ($items) use ($kriteriaList) {

        $row = [];

        // Nama siswa
        $row['nama'] = optional($items->first()->siswa)->nama_siswa ?? '-';

        // Default isi kosong
        foreach ($kriteriaList as $kriteria) {
            $row[$kriteria] = '-';
        }

        // Isi nilai
        foreach ($items as $item) {

            if ($item->kriteria) {

                $namaKriteria = $item->kriteria->nama_kriteria;

                $row[$namaKriteria] = $item->skalaNilai->keterangan ?? '-';
            }
        }

        return $row;
    });

    return view('admin.laporan.penilaian', compact(
        'data',
        'kriteriaList'
    ));
}

    public function cetakPenilaian()
{
    $nilai = \App\Models\NilaiPerkembangan::with([
        'siswa.kelas',
        'kriteria'
    ])->get();

    // =========================
    // LIST KRITERIA
    // =========================
    $kriteriaList = $nilai
        ->pluck('kriteria.nama_kriteria')
        ->unique()
        ->values();

    // =========================
    // DATA TABEL SISWA
    // =========================
    $grouped = $nilai->groupBy('siswa_id');

    $data = [];

    foreach ($grouped as $siswaId => $items) {

        $row = [];

        $row['nama'] = $items->first()->siswa->nama_siswa ?? '-';

        foreach ($kriteriaList as $kriteria) {

            $nilaiItem = $items->first(function ($item) use ($kriteria) {
                return $item->kriteria->nama_kriteria == $kriteria;
            });

            $row[$kriteria] = $nilaiItem->keterangan ?? '-';
        }

        $data[] = $row;
    }

    // =========================
    // STATISTIK
    // =========================
    $jumlahBB = $nilai->where('keterangan', 'BB')->count();
    $jumlahMB = $nilai->where('keterangan', 'MB')->count();
    $jumlahBSH = $nilai->where('keterangan', 'BSH')->count();
    $jumlahBSB = $nilai->where('keterangan', 'BSB')->count();

    // =========================
    // REKAP PER KELAS
    // =========================
    $rekapKelas = [];

    $kelasGroup = $nilai->groupBy(function ($item) {
        return $item->siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
    });

    foreach ($kelasGroup as $kelas => $items) {

        $rekapKelas[] = [
            'kelas' => $kelas,
            'jumlah' => $items->pluck('siswa_id')->unique()->count(),
            'bsb' => $items->where('keterangan', 'BSB')->count(),
            'bsh' => $items->where('keterangan', 'BSH')->count(),
            'mb' => $items->where('keterangan', 'MB')->count(),
            'bb' => $items->where('keterangan', 'BB')->count(),
        ];
    }

    return view('admin.laporan.cetak_penilaian', compact(
        'data',
        'kriteriaList',
        'jumlahBB',
        'jumlahMB',
        'jumlahBSH',
        'jumlahBSB',
        'rekapKelas'
    ));
}

    public function downloadPenilaian()
{
    $rawData = NilaiPerkembangan::with(['siswa', 'kriteria'])->get();

    $kriteriaList = $rawData
        ->pluck('kriteria.nama_kriteria')
        ->filter()
        ->unique()
        ->values();

    $data = $rawData->groupBy('siswa_id')->map(function ($items) use ($kriteriaList) {

        $row = [];

        $row['nama'] = optional($items->first()->siswa)->nama_siswa ?? '-';

        foreach ($kriteriaList as $kriteria) {
            $row[$kriteria] = '-';
        }

        foreach ($items as $item) {

            if ($item->kriteria) {

                $namaKriteria = $item->kriteria->nama_kriteria;

                $row[$namaKriteria] = $item->keterangan;
            }
        }

        return $row;
    });

    $pdf = Pdf::loadView('admin.laporan.cetak_penilaian', compact(
        'data',
        'kriteriaList'
    ) + ['pdf' => true]);

    $pdf->setPaper('A4', 'landscape');

    return $pdf->download('laporan-penilaian-anak.pdf');
}
}