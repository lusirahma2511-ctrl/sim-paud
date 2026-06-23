<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\HariLibur;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->get('tipe', 'guru');
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $kelas_id = $request->get('kelas_id');

        // Hitung semester dan tahun ajaran dari tanggal
        $bulan = date('n', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        if ($bulan >= 7) {
            $semester = 1;
            $tahunAjaran = $tahun . '/' . ($tahun + 1);
        } else {
            $semester = 2;
            $tahunAjaran = ($tahun - 1) . '/' . $tahun;
        }

        $kelas = Kelas::all();
        $guru = Guru::all();
        $siswa = Siswa::all();

        // Check if it's a holiday OR a weekend (Saturday/Sunday)
        $isHariLibur = HariLibur::where('tanggal', $tanggal)->exists();
        $dayOfWeek = date('N', strtotime($tanggal)); // 1 = Monday, 6 = Saturday, 7 = Sunday
        $isWeekend = in_array($dayOfWeek, [6, 7]);
        
        $isNoPresensi = $isHariLibur || $isWeekend;

        if ($tipe == 'guru') {
            if (!$isNoPresensi) {
                foreach ($guru as $g) {
                    PresensiGuru::firstOrCreate(
                        ['guru_id' => $g->id, 'tanggal' => $tanggal],
                        ['status' => 'alpha']
                    );
                }
            }
            
            $presensi = PresensiGuru::with('guru')
                ->where('tanggal', $tanggal)
                ->get();
        } else {
            $siswaFiltered = Siswa::when($kelas_id, fn($q) => $q->where('kelas_id', $kelas_id))->get();
            
            if (!$isNoPresensi) {
                foreach ($siswaFiltered as $s) {
                    PresensiSiswa::firstOrCreate(
                        ['siswa_id' => $s->id, 'tanggal' => $tanggal],
                        ['kelas_id' => $s->kelas_id, 'status' => 'alpha', 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran]
                    );
                }
            }
            
            $presensi = PresensiSiswa::with(['siswa', 'kelas'])
                ->where('tanggal', $tanggal)
                ->when($kelas_id, fn($q) => $q->where('kelas_id', $kelas_id))
                ->get();
        }

        $rekap = [
            'hadir' => $presensi->where('status', 'hadir')->count(),
            'sakit' => $presensi->where('status', 'sakit')->count(),
            'izin' => $presensi->where('status', 'izin')->count(),
            'alpha' => $presensi->where('status', 'alpha')->count(),
        ];

        return view('presensi.index', compact(
            'presensi','tipe','tanggal','kelas_id','rekap','kelas','guru','siswa','isHariLibur','isWeekend','isNoPresensi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_presensi' => 'required|in:guru,siswa',
            'user_id' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        // Hitung semester dan tahun ajaran dari tanggal
        $bulan = date('n', strtotime($request->tanggal));
        $tahun = date('Y', strtotime($request->tanggal));
        if ($bulan >= 7) {
            $semester = 1;
            $tahunAjaran = $tahun . '/' . ($tahun + 1);
        } else {
            $semester = 2;
            $tahunAjaran = ($tahun - 1) . '/' . $tahun;
        }

        if ($request->tipe_presensi == 'guru') {

            PresensiGuru::create([
                'guru_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'status' => strtolower($request->status),
            ]);

        } else {

            // 🔥 FIX ERROR kelas_id
            $siswa = Siswa::findOrFail($request->user_id);

            PresensiSiswa::create([
                'siswa_id' => $request->user_id,
                'kelas_id' => $siswa->kelas_id, // WAJIB
                'tanggal' => $request->tanggal,
                'status' => strtolower($request->status),
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran,
            ]);
        }

        return redirect()->route('admin.presensi.index', [
            'tipe' => $request->tipe_presensi
        ])->with('success', 'Berhasil tambah');
    }

    public function edit($id, Request $request)
    {
        $tipe = $request->get('tipe', 'guru');
        
        if ($tipe == 'guru') {
            $presensi = PresensiGuru::findOrFail($id);
        } else {
            $presensi = PresensiSiswa::findOrFail($id);
        }

        return $presensi;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipe_presensi' => 'required|in:guru,siswa',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        // Hitung semester dan tahun ajaran dari tanggal
        $bulan = date('n', strtotime($request->tanggal));
        $tahun = date('Y', strtotime($request->tanggal));
        if ($bulan >= 7) {
            $semester = 1;
            $tahunAjaran = $tahun . '/' . ($tahun + 1);
        } else {
            $semester = 2;
            $tahunAjaran = ($tahun - 1) . '/' . $tahun;
        }

        if ($request->tipe_presensi == 'guru') {
            $presensi = PresensiGuru::findOrFail($id);
            $presensi->update([
                'tanggal' => $request->tanggal,
                'status' => strtolower($request->status),
            ]);
        } else {
            $presensi = PresensiSiswa::findOrFail($id);
            $presensi->update([
                'tanggal' => $request->tanggal,
                'status' => strtolower($request->status),
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran,
            ]);
        }

        return redirect()->route('admin.presensi.index', [
            'tipe' => $request->tipe_presensi
        ])->with('success', 'Berhasil update');
    }

    public function destroy(Request $request, $id)
    {
        $tipe = $request->get('tipe');

        if ($tipe == 'guru') {
            PresensiGuru::findOrFail($id)->delete();
        } else {
            PresensiSiswa::findOrFail($id)->delete();
        }

        return redirect()->route('admin.presensi.index', [
            'tipe' => $tipe
        ])->with('success', 'Berhasil hapus');
    }

    public function rekap(Request $request)
{
    $tipe = $request->get('tipe', 'guru');
    $bulan = (int) $request->get('bulan', date('n'));
    $tahun = (int) $request->get('tahun', date('Y'));
    $kelas_id = $request->get('kelas_id');

    $kelas = Kelas::all();
    
    // Get all holidays in the selected month and year
    $hariLibur = HariLibur::whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->pluck('tanggal')
        ->toArray();

    if ($tipe == 'guru') {

        $dataUser = Guru::all();

        $presensi = PresensiGuru::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $rekap = [];

        foreach ($dataUser as $g) {
            $data = $presensi->where('guru_id', $g->id)
                ->whereNotIn('tanggal', $hariLibur); // Exclude holidays

            $rekap[] = [
                'nama' => $g->nama_guru,
                'hadir' => $data->where('status', 'hadir')->count(),
                'sakit' => $data->where('status', 'sakit')->count(),
                'izin' => $data->where('status', 'izin')->count(),
                'alpha' => $data->where('status', 'alpha')->count(),
                'total' => $data->count(),
            ];
        }

    } else {

        $dataUser = Siswa::when($kelas_id, fn($q) => $q->where('kelas_id', $kelas_id))->get();

        $presensi = PresensiSiswa::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->when($kelas_id, fn($q) => $q->where('kelas_id', $kelas_id))
            ->get();

        $rekap = [];

        foreach ($dataUser as $s) {
            $data = $presensi->where('siswa_id', $s->id)
                ->whereNotIn('tanggal', $hariLibur); // Exclude holidays

            $rekap[] = [
                'nama' => $s->nama_siswa,
                'hadir' => $data->where('status', 'hadir')->count(),
                'sakit' => $data->where('status', 'sakit')->count(),
                'izin' => $data->where('status', 'izin')->count(),
                'alpha' => $data->where('status', 'alpha')->count(),
                'total' => $data->count(),
            ];
        }
    }

    return view('presensi.rekap', compact(
        'tipe','bulan','tahun','rekap','kelas','kelas_id','hariLibur'
    ));
}
}