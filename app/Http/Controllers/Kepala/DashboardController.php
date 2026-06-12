<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use App\Models\NilaiPerkembangan;
use App\Models\Kriteria;
use App\Models\SkalaNilai;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // =========================
    // DASHBOARD KEPALA SEKOLAH
    // =========================
    public function index()
    {
        $kriteria = Kriteria::with('nilaiPerkembangans')->get();

        $labels = [];
        $data = [];

        foreach ($kriteria as $k) {

            $labels[] = $k->nama_kriteria;

            $avg = $k->nilaiPerkembangans->avg('nilai');

            $data[] = $avg ? round($avg, 2) : 0;
        }

        // Statistik tambahan
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();

        return view('kepala.dashboard', compact(
            'labels',
            'data',
            'totalSiswa',
            'totalGuru',
            'totalKelas'
        ));
    }

    // =========================
    // PRESENSI SISWA
    // =========================
    public function presensiSiswa(Request $request)
    {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));
        $kelasId = $request->get('kelas_id');

        $kelas = Kelas::all();
        
        $siswaQuery = Siswa::with(['kelas', 'presensi' => function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }]);

        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $siswas = $siswaQuery->get();

        $tabelData = $siswas->map(function($siswa) {
            return [
                'nama' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'hadir' => $siswa->presensi->whereIn('status', ['Hadir', 'hadir'])->count(),
                'sakit' => $siswa->presensi->whereIn('status', ['Sakit', 'sakit'])->count(),
                'izin' => $siswa->presensi->whereIn('status', ['Izin', 'izin'])->count(),
                'alpha' => $siswa->presensi->whereIn('status', ['Alpha', 'alpha'])->count(),
            ];
        });

        return view('kepala.presensi_siswa', compact('tabelData', 'kelas', 'kelasId', 'bulan', 'tahun'));
    }

    // =========================
    // PRESENSI GURU
    // =========================
    public function presensiGuru(Request $request)
    {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $gurus = Guru::with(['presensi' => function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }])->get();

        $tabelData = $gurus->map(function($guru) {
            return [
                'nama' => $guru->nama_guru,
                'jabatan' => $guru->jabatan ?? '-',
                'hadir' => $guru->presensi->whereIn('status', ['Hadir', 'hadir'])->count(),
                'sakit' => $guru->presensi->whereIn('status', ['Sakit', 'sakit'])->count(),
                'izin' => $guru->presensi->whereIn('status', ['Izin', 'izin'])->count(),
                'alpha' => $guru->presensi->whereIn('status', ['Alpha', 'alpha'])->count(),
            ];
        });

        // Statistik
        $totalPresensi = $tabelData->sum('hadir') + $tabelData->sum('sakit') + $tabelData->sum('izin') + $tabelData->sum('alpha');
        $totalHadir = $tabelData->sum('hadir');
        $totalIzin = $tabelData->sum('izin');
        $totalSakit = $tabelData->sum('sakit');

        return view('kepala.presensi_guru', compact(
            'tabelData',
            'bulan',
            'tahun',
            'totalPresensi',
            'totalHadir',
            'totalIzin',
            'totalSakit'
        ));
    }

    // =========================
    // LAPORAN PENILAIAN
    // =========================
    public function penilaian(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $kelas = Kelas::all();
        $kriterias = Kriteria::orderBy('kode')->get();
        $skalaNilai = SkalaNilai::all();

        // Ambil data siswa berdasarkan filter kelas
        $siswaQuery = Siswa::with(['kelas', 'nilai_perkembangans.kriteria']);
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }
        $siswas = $siswaQuery->get();

        // Format data untuk tabel (Siswa sebagai baris, Kriteria sebagai kolom)
        $tabelData = [];
        foreach ($siswas as $siswa) {
            $nilaiSiswa = [];
            foreach ($kriterias as $k) {
                $nilaiObj = $siswa->nilai_perkembangans->where('kriteria_id', $k->id)->first();
                
                if ($nilaiObj) {
                    $teksSkala = $skalaNilai->where('nilai', $nilaiObj->nilai)->first()->keterangan ?? '-';
                    
                    // Tentukan Singkatan
                    $singkatan = '-';
                    if (stripos($teksSkala, 'Sangat Baik') !== false) $singkatan = 'SB';
                    elseif (stripos($teksSkala, 'Sesuai Harapan') !== false) $singkatan = 'BSH';
                    elseif (stripos($teksSkala, 'Mulai Berkembang') !== false) $singkatan = 'MB';
                    elseif (stripos($teksSkala, 'Belum Berkembang') !== false) $singkatan = 'BB';

                    $nilaiSiswa[$k->id] = [
                        'angka' => $nilaiObj->nilai,
                        'skala' => $singkatan
                    ];
                } else {
                    $nilaiSiswa[$k->id] = null;
                }
            }
            $tabelData[] = [
                'nama' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'nilai' => $nilaiSiswa
            ];
        }

        // Data untuk Grafik (Rata-rata per kriteria dari semua data mentah)
        $queryMentah = NilaiPerkembangan::query();
        if ($kelasId) {
            $queryMentah->whereHas('siswa', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }
        $dataMentah = $queryMentah->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($kriterias as $k) {
            $chartLabels[] = $k->nama_kriteria;
            $avg = $dataMentah->where('kriteria_id', $k->id)->avg('nilai');
            $chartData[] = $avg ? round($avg, 2) : 0;
        }

        return view('kepala.laporan', compact('tabelData', 'kriterias', 'kelas', 'kelasId', 'chartLabels', 'chartData'));
    }
}