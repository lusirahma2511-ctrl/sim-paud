<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Kriteria;
use App\Models\SkalaNilai;
use App\Models\NilaiPerkembangan;
use App\Models\PresensiSiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ERaporController extends Controller
{
    public function index(Request $request)
    {
        $kelas_id = $request->get('kelas_id');
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

        // Sort classes: alif, ba, ta first
        $kelas = Kelas::all()->sortBy(function($k) {
            $order = ['alif' => 1, 'ba' => 2, 'ta' => 3];
            $name = strtolower(trim($k->nama_kelas));
            return $order[$name] ?? 99;
        })->values();
        $siswas = []; 
        $kriterias = Kriteria::all();

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)
                ->withCount(['nilai_perkembangans' => function($q) use ($semester, $tahunAjaran) {
                    $q->where('semester', $semester)->where('tahun_ajaran', $tahunAjaran);
                }])
                ->get();
        }

        return view('erapor.index', compact('kelas', 'kelas_id', 'siswas', 'kriterias', 'semester', 'tahunAjaran', 'tahunAjaranOptions'));
    }

    public function show(Request $request, $siswa_id)
    {
        $siswa = Siswa::with(['kelas', 'orangTua'])->findOrFail($siswa_id);
        $semester = $request->get('semester', 1);
        $currentYear = date('Y');
        $defaultTahunAjaran = $currentYear . '/' . ($currentYear + 1);
        $tahunAjaran = $request->get('tahun_ajaran', $defaultTahunAjaran);
        $kriterias = Kriteria::orderBy('kode')->get();
        $skalaNilai = SkalaNilai::all();

        $nilaiPerkembangans = NilaiPerkembangan::where('siswa_id', $siswa_id)->where('semester', $semester)->where('tahun_ajaran', $tahunAjaran)->get();
        
        $nilaiArray = [];
        foreach ($nilaiPerkembangans as $nilai) {
            $keterangan = $skalaNilai->where('nilai', $nilai->nilai)->first()->keterangan ?? '-';
            $nilaiArray[$nilai->kriteria_id] = [
                'angka' => $nilai->nilai,
                'teks' => $keterangan
            ];
        }

        $sawResult = $this->calculateSAW($siswa_id, $kriterias, $semester, $tahunAjaran);
        
        return view('erapor.show', compact('siswa', 'kriterias', 'nilaiArray', 'sawResult', 'semester', 'tahunAjaran'));
    }

    private function calculateSAW($siswa_id, $kriterias, $semester, $tahunAjaran = null)
    {
        $siswaTerpilih = Siswa::find($siswa_id);
        if (!$siswaTerpilih) return ['total' => 0, 'rank' => '-', 'all_results' => []];

        $allSiswa = Siswa::where('kelas_id', $siswaTerpilih->kelas_id)->get();
        $matrix = [];
        $maxValues = [];

        foreach ($kriterias as $kriteria) {
            $query = NilaiPerkembangan::where('kriteria_id', $kriteria->id)
                ->where('semester', $semester);
            if ($tahunAjaran) {
                $query->where('tahun_ajaran', $tahunAjaran);
            }
            $nilaiKriteria = $query->whereIn('siswa_id', $allSiswa->pluck('id'))
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

    public function print($siswa_id)
    {
        $siswa = Siswa::with(['kelas.guru', 'orangTua'])->findOrFail($siswa_id);
        $semester = request('semester', 1);
        $currentYear = date('Y');
        $defaultTahunAjaran = $currentYear . '/' . ($currentYear + 1);
        $tahunAjaran = request('tahun_ajaran', $defaultTahunAjaran);
        // Get available semesters for this student
        $availableSemesters = NilaiPerkembangan::where('siswa_id', $siswa_id)->where('tahun_ajaran', $tahunAjaran)->distinct()->pluck('semester')->sort()->toArray();
        if (empty($availableSemesters)) $availableSemesters = [1];

        $semesterData = [];
        foreach ($availableSemesters as $s) {
            $kriterias = Kriteria::orderBy('kode')->get();
            $skalaNilai = SkalaNilai::all();
            
            $nilaiPerkembangans = NilaiPerkembangan::where('siswa_id', $siswa_id)->where('semester', $s)->where('tahun_ajaran', $tahunAjaran)->get();
            $nilaiArray = [];
            foreach ($nilaiPerkembangans as $nilai) {
                $keterangan = $skalaNilai->where('nilai', $nilai->nilai)->first()->keterangan ?? '-';
                $nilaiArray[$nilai->kriteria_id] = [
                    'angka' => $nilai->nilai,
                    'teks' => $keterangan
                ];
            }

            $sawResult = $this->calculateSAW($siswa_id, $kriterias, $s, $tahunAjaran);
            
            $semesterData[] = [
                'semester' => $s,
                'kriterias' => $kriterias,
                'nilaiArray' => $nilaiArray,
                'sawResult' => $sawResult
            ];
        }

        $presensi = [
            'sakit' => PresensiSiswa::where('siswa_id', $siswa_id)->where('status', 'sakit')->count(),
            'izin'  => PresensiSiswa::where('siswa_id', $siswa_id)->where('status', 'izin')->count(),
            'alpha' => PresensiSiswa::where('siswa_id', $siswa_id)->where('status', 'alpha')->count(),
        ];

        $logoBase64 = asset('images/logo paud rmv.png');
        $fotoSiswaBase64 = $siswa->foto ? asset('storage/' . $siswa->foto) : null;

        // Tanda Tangan Otomatis
        $kepalaSekolah = \App\Models\Guru::where('jabatan', 'Kepala Sekolah')->first();
        $guruKelas = $siswa->kelas->guru ?? null;
        $namaIbu = $siswa->orangTua->nama_ibu ?? '-';

        return view('erapor.print', compact(
            'siswa', 'semesterData', 'presensi', 'logoBase64', 'fotoSiswaBase64',
            'kepalaSekolah', 'guruKelas', 'namaIbu'
        ));
    }

    public function download($id)
    {
        $siswa = Siswa::with(['kelas.guru', 'orangTua'])->findOrFail($id);
        $semester = request('semester', 1);
        $currentYear = date('Y');
        $defaultTahunAjaran = $currentYear . '/' . ($currentYear + 1);
        $tahunAjaran = request('tahun_ajaran', $defaultTahunAjaran);
        // Get available semesters
        $availableSemesters = NilaiPerkembangan::where('siswa_id', $siswa->id)->where('tahun_ajaran', $tahunAjaran)->distinct()->pluck('semester')->sort()->toArray();
        if (empty($availableSemesters)) $availableSemesters = [1];

        $semesterData = [];
        foreach ($availableSemesters as $s) {
            $kriterias = Kriteria::orderBy('kode')->get();
            $skalaNilai = SkalaNilai::all();
            
            $nilaiPerkembangans = NilaiPerkembangan::where('siswa_id', $siswa->id)->where('semester', $s)->where('tahun_ajaran', $tahunAjaran)->get();
            $nilaiArray = [];
            foreach ($nilaiPerkembangans as $nilai) {
                $keterangan = $skalaNilai->where('nilai', $nilai->nilai)->first()->keterangan ?? '-';
                $nilaiArray[$nilai->kriteria_id] = [
                    'angka' => $nilai->nilai,
                    'teks' => $keterangan
                ];
            }

            $sawResult = $this->calculateSAW($id, $kriterias, $s, $tahunAjaran);
            
            $semesterData[] = [
                'semester' => $s,
                'kriterias' => $kriterias,
                'nilaiArray' => $nilaiArray,
                'sawResult' => $sawResult
            ];
        }

        $presensi = [
            'sakit' => PresensiSiswa::where('siswa_id', $id)->where('status', 'sakit')->count(),
            'izin'  => PresensiSiswa::where('siswa_id', $id)->where('status', 'izin')->count(),
            'alpha' => PresensiSiswa::where('siswa_id', $id)->where('status', 'alpha')->count(),
        ];

        // 1. Logo base64
        $logoBase64 = null;
        $pathLogo = public_path('images/logo paud rmv.png');
        if (file_exists($pathLogo)) {
            $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($pathLogo));
        }

        // 2. Foto siswa base64
        $fotoSiswaBase64 = null;
        if ($siswa->foto && file_exists(public_path('storage/' . $siswa->foto))) {
            $type = pathinfo(public_path('storage/' . $siswa->foto), PATHINFO_EXTENSION);
            $fotoSiswaBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(public_path('storage/' . $siswa->foto)));
        }

        // 3. Foto Ilustrasi Anak base64
        $fotoIlustrasiBase64 = null;
        $pathIlustrasi = public_path('images/foto anak.png');
        if (file_exists($pathIlustrasi)) {
            $type = pathinfo($pathIlustrasi, PATHINFO_EXTENSION);
            $fotoIlustrasiBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($pathIlustrasi));
        }

        // Tanda Tangan Otomatis
        $kepalaSekolah = \App\Models\Guru::where('jabatan', 'Kepala Sekolah')->first();
        $guruKelas = $siswa->kelas->guru ?? null;
        $namaIbu = $siswa->orangTua->nama_ibu ?? '-';

        $pdf = Pdf::loadView('erapor.print', compact(
            'siswa', 'semesterData', 'presensi', 'logoBase64', 'fotoSiswaBase64', 'fotoIlustrasiBase64',
            'kepalaSekolah', 'guruKelas', 'namaIbu'
        ) + ['pdf' => true])->setPaper('a4', 'portrait');

        return $pdf->download('rapor-'.$siswa->nama_siswa.'.pdf');
    }


    public function getSiswaByKelas(Request $request)
    {
        $siswa = Siswa::where('kelas_id', $request->kelas_id)->select('id', 'nisn', 'nama_siswa')->get();
        return response()->json($siswa);
    }
}
