<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\NilaiPerkembangan;
use App\Models\PresensiSiswa;
use Illuminate\Support\Facades\Auth;

use App\Models\Kriteria;
use App\Models\SkalaNilai;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orangtua = $user->orangtua;
        
        // If no direct orangtua relation, try to find via siswa (old way)
        if (!$orangtua) {
            $currentSiswa = Siswa::where('nisn', $user->username)->first();
            if ($currentSiswa) {
                $orangtua = $currentSiswa->orangTua;
            }
        }

        if (!$orangtua) {
            return view('orangtua.dashboard', [
                'siswas' => collect(),
                'nilaiPerkembangan' => collect(),
                'presensi' => collect(),
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
            ]);
        }

        // GET ALL SISWA BELONGING TO PARENTS WITH SAME nama_ayah AND nama_ibu
        $siswas = Siswa::with('kelas')
            ->whereHas('orangTua', function($q) use ($orangtua) {
                $q->where('nama_ayah', $orangtua->nama_ayah)
                  ->where('nama_ibu', $orangtua->nama_ibu);
            })
            ->get();

        $siswaIds = $siswas->pluck('id');

        $nilaiPerkembangan = NilaiPerkembangan::with('kriteria')
                                ->whereIn('siswa_id', $siswaIds)
                                ->get();

        $presensi = PresensiSiswa::whereIn('siswa_id', $siswaIds)
                        ->latest()
                        ->limit(10)
                        ->get();

        $hadir = $presensi->where('status', 'Hadir')->count();
        $sakit = $presensi->where('status', 'Sakit')->count();
        $izin  = $presensi->where('status', 'Izin')->count();
        $alpha = $presensi->where('status', 'Alpha')->count();

        return view('orangtua.dashboard', compact(
            'siswas',
            'nilaiPerkembangan',
            'presensi',
            'hadir',
            'sakit',
            'izin',
            'alpha'
        ));
    }

    // HALAMAN RAPOR
    public function raporIndex()
    {
        $user = Auth::user();
        $orangtua = $user->orangtua;
        
        // If no direct orangtua relation, try to find via siswa (old way)
        if (!$orangtua) {
            $currentSiswa = Siswa::where('nisn', $user->username)->first();
            if ($currentSiswa) {
                $orangtua = $currentSiswa->orangTua;
            }
        }

        if (!$orangtua) {
            return redirect()->back()->with('error', 'Data orang tua tidak ditemukan');
        }

        // GET ALL SISWA BELONGING TO PARENTS WITH SAME nama_ayah AND nama_ibu
        $siswas = Siswa::with('kelas')
            ->whereHas('orangTua', function($q) use ($orangtua) {
                $q->where('nama_ayah', $orangtua->nama_ayah)
                  ->where('nama_ibu', $orangtua->nama_ibu);
            })
            ->get();

        // Ambil ID siswa
        $siswaIds = $siswas->pluck('id');

        // Ambil nilai perkembangan
        $nilai = NilaiPerkembangan::with(['kriteria', 'siswa.kelas'])
                    ->whereIn('siswa_id', $siswaIds)
                    ->get();

        return view('orangtua.rapor', compact(
            'siswas',
            'nilai'
        ));
    }

    public function raporShow($siswa_id)
    {
        $user = Auth::user();
        $orangtua = $user->orangtua;
        
        // If no direct orangtua relation, try to find via siswa (old way)
        if (!$orangtua) {
            $currentSiswaLogin = Siswa::where('nisn', $user->username)->first();
            if ($currentSiswaLogin) {
                $orangtua = $currentSiswaLogin->orangTua;
            }
        }

        if (!$orangtua) {
            abort(404, 'Data orang tua tidak ditemukan');
        }

        // Pastikan siswa yang diakses adalah anak dari orang tua yang login (same nama ayah dan ibu)
        $siswa = Siswa::with(['kelas', 'orangTua'])
            ->where('id', $siswa_id)
            ->whereHas('orangTua', function($q) use ($orangtua) {
                $q->where('nama_ayah', $orangtua->nama_ayah)
                  ->where('nama_ibu', $orangtua->nama_ibu);
            })
            ->firstOrFail();

        $kriterias = Kriteria::orderBy('kode')->get();
        $skalaNilai = SkalaNilai::all();

        $nilaiPerkembangans = NilaiPerkembangan::where('siswa_id', $siswa_id)->get();
        
        $nilaiArray = [];
        foreach ($nilaiPerkembangans as $nilai) {
            $keterangan = $skalaNilai->where('nilai', $nilai->nilai)->first()->keterangan ?? '-';
            $nilaiArray[$nilai->kriteria_id] = [
                'angka' => $nilai->nilai,
                'teks' => $keterangan
            ];
        }

        $sawResult = $this->calculateSAW($siswa_id, $kriterias);
        
        return view('orangtua.rapor_show', compact('siswa', 'kriterias', 'nilaiArray', 'sawResult'));
    }

    private function calculateSAW($siswa_id, $kriterias)
    {
        $siswaTerpilih = Siswa::find($siswa_id);
        if (!$siswaTerpilih) return ['total' => 0, 'rank' => '-', 'all_results' => []];

        $allSiswa = Siswa::where('kelas_id', $siswaTerpilih->kelas_id)->get();
        $matrix = [];
        $maxValues = [];

        foreach ($kriterias as $kriteria) {
            $nilaiKriteria = NilaiPerkembangan::where('kriteria_id', $kriteria->id)
                ->whereIn('siswa_id', $allSiswa->pluck('id'))
                ->pluck('nilai', 'siswa_id')
                ->toArray();
            
            $maxValues[$kriteria->id] = !empty($nilaiKriteria) ? max($nilaiKriteria) : 100;
            if ($maxValues[$kriteria->id] <= 0) $maxValues[$kriteria->id] = 1; 

            foreach ($allSiswa as $s) {
                $matrix[$s->id][$kriteria->id] = $nilaiKriteria[$s->id] ?? 0;
            }
        }

        $result = [];
        foreach ($allSiswa as $s) {
            $totalNilaiTerbobot = 0;
            foreach ($kriterias as $kriteria) {
                $nilai = $matrix[$s->id][$kriteria->id] ?? 0;
                $normalisasi = $nilai / $maxValues[$kriteria->id];
                $totalNilaiTerbobot += ($normalisasi * (float)$kriteria->bobot);
            }
            $result[$s->id] = ['total' => round($totalNilaiTerbobot, 3), 'rank' => 0];
        }

        arsort($result);
        $rank = 1;
        foreach ($result as $id => &$val) { $val['rank'] = $rank++; }

        return [
            'total' => $result[$siswa_id]['total'] ?? 0,
            'rank' => $result[$siswa_id]['rank'] ?? '-',
            'all_results' => $result,
        ];
    }
}