<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SkalaNilai; // Sudah benar
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Kriteria;
use App\Models\NilaiPerkembangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SAWService;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            $guru = Guru::whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%'])->first();
            
            if ($guru && empty($guru->user_id)) {
                $guru->update(['user_id' => $user->id]);
            }
        }

        $myKelas = null;
        if ($guru) {
            $myKelas = Kelas::where('guru_id', $guru->id)->first();
        }

        if (!$myKelas) {
            $myKelas = Kelas::whereHas('guru', function($q) use ($user) {
                $q->whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%']);
            })->first();
        }

        // Sort classes: alif, ba, ta
        $kelas = Kelas::with('guru')->get()->sortBy(function($k) {
            $order = ['alif' => 1, 'ba' => 2, 'ta' => 3];
            $name = strtolower(trim($k->nama_kelas));
            return $order[$name] ?? 99;
        })->values();
        
        $isGuruKelas = false;
        $semester = $request->get('semester', 1);
        $currentYear = date('Y');
        $defaultTahunAjaran = $currentYear . '/' . ($currentYear + 1);
        $tahunAjaran = $request->get('tahun_ajaran', $defaultTahunAjaran);

        // Generate list of tahun ajaran options (last 5 years to next 1 year)
        $tahunAjaranOptions = [];
        for ($i = 5; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $tahunAjaranOptions[] = $year . '/' . ($year + 1);
        }

        if ($user->role === 'guru_kelas' || $myKelas) {
            $isGuruKelas = true;
            if (!$myKelas) {
                return redirect()->route('guru.dashboard')->with('error', 'Sistem tidak menemukan Kelas yang diampu oleh ' . $user->name . '. Pastikan di menu Admin > Data Kelas, nama tersebut sudah dipilih dengan benar pada kolom Guru Kelas.');
            }
            $kelas = Kelas::where('id', $myKelas->id)->with('guru')->get();
            $kelas_id = $myKelas->id;
        } else {
            $kelas_id = $request->get('kelas_id', $myKelas ? $myKelas->id : null);
        }

        if (!$myKelas && $user->role !== 'admin') {
            return redirect()->route('guru.dashboard')->with('error', 'Akses ditolak! Menu Input Nilai hanya untuk Guru Kelas.');
        }

        $kriterias = Kriteria::orderBy('kode')->get();
        $skalas = SkalaNilai::orderBy('nilai', 'desc')->get();

        return view('guru.nilai.index', compact('kelas', 'kriterias', 'kelas_id', 'skalas', 'myKelas', 'isGuruKelas', 'semester', 'tahunAjaran', 'tahunAjaranOptions'));
    }

    /**
     * API untuk ambil siswa berdasarkan kelas (khusus guru)
     */
    public function getSiswa(Request $request)
    {
        $user = Auth::user();
        $kelas_id = $request->get('kelas_id');
        $semester = $request->get('semester', 1);
        $tahunAjaran = $request->get('tahun_ajaran');
        
        if (!$kelas_id) {
            return response()->json([]);
        }

        // PROTEKSI API: Jika role guru_kelas, pastikan dia punya akses ke kelas tersebut
        if ($user->role === 'guru_kelas') {
            // Gunakan logika pencarian yang sama fleksibelnya dengan halaman index
            $guru = Guru::where('user_id', $user->id)->first();
            if (!$guru) {
                $guru = Guru::whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%'])->first();
            }

            $myKelas = null;
            if ($guru) {
                $myKelas = Kelas::where('guru_id', $guru->id)->first();
            }

            // Fallback cari kelas berdasarkan nama user jika ID belum sinkron
            if (!$myKelas) {
                $myKelas = Kelas::whereHas('guru', function($q) use ($user) {
                    $q->whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%']);
                })->first();
            }
            
            // Jika kelas yang diminta bukan kelasnya, tolak akses (403)
            if (!$myKelas || $myKelas->id != $kelas_id) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke kelas ini.'], 403);
            }
        }

        $siswa = Siswa::where('kelas_id', $kelas_id)
            ->where('status', 'Aktif')
            ->with(['nilai_perkembangans' => function($query) use ($semester, $tahunAjaran) {
                $query->select('id', 'siswa_id', 'kriteria_id', 'nilai')->where('semester', $semester);
                if ($tahunAjaran) {
                    $query->where('tahun_ajaran', $tahunAjaran);
                }
            }])
            ->orderBy('nama_siswa', 'asc')
            ->get();

        return response()->json($siswa);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'array',
            // Diubah ke nullable karena nilai dikirim dari select/dropdown
            'nilai.*.*' => 'nullable|numeric|min:0|max:100',
            'semester' => 'required|integer|in:1,2',
            'tahun_ajaran' => 'required|string',
        ]);

        $user = Auth::user();
        $semester = $request->get('semester', 1);
        $tahunAjaran = $request->get('tahun_ajaran');
        
        // Cari data Guru yang terhubung dengan akun login ini
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            $guru = Guru::whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%'])->first();
        }

        if (!$guru) {
            return back()->with('error', 'Gagal menyimpan: Data Guru tidak ditemukan untuk akun ini.');
        }

        foreach ($request->nilai as $siswa_id => $nilaiKriteria) {
            foreach ($nilaiKriteria as $kriteria_id => $nilai) {
                
                if ($nilai === null || $nilai === '') {
                    continue;
                }

                NilaiPerkembangan::updateOrCreate(
                    [
                        'siswa_id'    => $siswa_id,
                        'kriteria_id' => $kriteria_id,
                        'semester' => $semester,
                        'tahun_ajaran' => $tahunAjaran,
                    ],
                    [
                        'nilai'   => $nilai,
                        'guru_id' => $guru->id, // GUNAKAN ID GURU DARI TABEL GURUS, BUKAN ID USER
                    ]
                );
            }
        }

        return back()->with('success', 'Semua nilai berhasil disimpan! 🔥');
    }

    public function riwayat(Request $request, SAWService $sawService)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            $guru = Guru::whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%'])->first();
        }

        $myKelas = null;
        if ($guru) {
            $myKelas = Kelas::where('guru_id', $guru->id)->first();
            if (!$myKelas) {
                $myKelas = Kelas::whereHas('guru', function($q) use ($user) {
                    $q->whereRaw('LOWER(nama_guru) LIKE ?', ['%' . strtolower(trim($user->name)) . '%']);
                })->first();
            }
        }

        $semester = $request->get('semester', 1);
        $currentYear = date('Y');
        $defaultTahunAjaran = $currentYear . '/' . ($currentYear + 1);
        $tahunAjaran = $request->get('tahun_ajaran', $defaultTahunAjaran);

        // Generate list of tahun ajaran options (last 5 years to next 1 year)
        $tahunAjaranOptions = [];
        for ($i = 5; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $tahunAjaranOptions[] = $year . '/' . ($year + 1);
        }

        // Ambil semua kelas untuk filter (Admin) atau batasi (Guru Kelas)
        if ($user->role === 'guru_kelas') {
            if (!$myKelas) {
                return redirect()->route('guru.dashboard')->with('error', 'Sistem tidak menemukan Kelas yang Anda ampu. Hubungi Admin.');
            }
            $kelas = Kelas::where('id', $myKelas->id)->get();
            $kelas_id = $myKelas->id; 
        } else {
            // Sort classes: alif, ba, ta
            $kelas = Kelas::all()->sortBy(function($k) {
                $order = ['alif' => 1, 'ba' => 2, 'ta' => 3];
                $name = strtolower(trim($k->nama_kelas));
                return $order[$name] ?? 99;
            })->values();
            $kelas_id = $request->get('kelas_id');
        }

        $kriterias = Kriteria::orderBy('id', 'asc')->get();

        $query = NilaiPerkembangan::with(['siswa.kelas', 'kriteria']);
        
        // Filter berdasarkan kelas_id yang sudah divalidasi
        if ($kelas_id) {
            $query->whereHas('siswa', function ($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id);
            });
        }
        
        // Filter by semester
        $query->where('semester', $semester);
        
        // Filter by tahun ajaran
        $query->where('tahun_ajaran', $tahunAjaran);

        $allNilai = $query->get();
        $nilaiPerkembangans = $allNilai->groupBy('siswa_id');

        $hasilSAW = [];
        $matriksNormalisasi = []; 

        if ($kelas_id && $nilaiPerkembangans->isNotEmpty()) {
            $matrix = [];
            $bobot = [];
            $tipe = [];
            $siswaDataMap = [];

            foreach ($kriterias as $k) {
                $bobot[$k->id] = (float)($k->bobot ?? 0);
                $tipe[$k->id] = 'benefit'; 
            }

            foreach ($nilaiPerkembangans as $siswaId => $items) {
                $siswa = $items->first()->siswa;
                $siswaDataMap[$siswaId] = $siswa;
                foreach ($kriterias as $k) {
                    $nilaiSiswa = $items->where('kriteria_id', $k->id)->first();
                    $matrix[$siswaId][$k->id] = $nilaiSiswa ? (float)$nilaiSiswa->nilai : 0;
                }
            }

            try {
                // 1. Matriks Normalisasi
                foreach ($kriterias as $k) {
                    $max = $allNilai->where('kriteria_id', $k->id)->max('nilai') ?: 1;
                    foreach ($matrix as $sId => $row) {
                        $matriksNormalisasi[$sId][$k->id] = round($row[$k->id] / $max, 3);
                    }
                }

                // 2. Hitung Skor Akhir
                $skorAkhir = $sawService->hitung($matrix, $bobot, $tipe);

                foreach ($skorAkhir as $sId => $skor) {
                    if (isset($siswaDataMap[$sId])) {
                        $hasilSAW[] = [
                            'siswa_id' => $sId,
                            'nama' => $siswaDataMap[$sId]->nama_siswa,
                            'nisn' => $siswaDataMap[$sId]->nisn,
                            'skor' => $skor
                        ];
                    }
                }

                // 3. Perangkingan
                usort($hasilSAW, function($a, $b) {
                    return $b['skor'] <=> $a['skor'];
                });

            } catch (\Exception $e) {
                \Log::error("SAW Error: " . $e->getMessage());
            }
        }

        return view('guru.nilai.riwayat', compact('nilaiPerkembangans', 'kelas', 'kriterias', 'kelas_id', 'hasilSAW', 'matriksNormalisasi', 'semester', 'tahunAjaran', 'tahunAjaranOptions'));
    }

    public function destroy($siswa_id)
{
    // Menghapus semua nilai perkembangan milik siswa tersebut
    NilaiPerkembangan::where('siswa_id', $siswa_id)->delete();

    return back()->with('success', 'Data riwayat nilai siswa berhasil dihapus! 🗑️');
}

}
