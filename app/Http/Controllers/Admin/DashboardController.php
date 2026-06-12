<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔢 TOTAL DATA
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();

        // 📊 SISWA PER KELAS
        $kelas = Kelas::withCount('siswa')->get();
        $labels = $kelas->pluck('nama_kelas');
        $data = $kelas->pluck('siswa_count');

        // 📅 PRESENSI HARI INI
        $today = Carbon::today();

        $totalHadirHariIni = PresensiSiswa::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $totalGuruHariIni = PresensiGuru::whereDate('tanggal', $today)
            ->count();

        // 📊 KEHADIRAN MINGGUAN (5 hari)
        $hari = [];
        $hadirMingguan = [];

        for ($i = 4; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);

            $hari[] = $tanggal->translatedFormat('D'); // Sen, Sel, dll

            $hadir = PresensiSiswa::whereDate('tanggal', $tanggal)
                ->where('status', 'hadir')
                ->count();

            $hadirMingguan[] = $hadir;
        }

        // 🧑‍🏫 GURU AKTIF HARI INI
        $guruAktif = PresensiGuru::with('guru')
            ->whereDate('tanggal', $today)
            ->get()
            ->pluck('guru.nama_guru');

        // 🔔 AKTIVITAS DINAMIS
        $aktivitas = [];

        if ($totalHadirHariIni > 0) {
            $aktivitas[] = "$totalHadirHariIni siswa hadir hari ini";
        }

        if ($totalGuruHariIni > 0) {
            $aktivitas[] = "$totalGuruHariIni guru melakukan presensi";
        }

        if ($totalSiswa > 0) {
            $aktivitas[] = "Total siswa saat ini: $totalSiswa";
        }

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'labels',
            'data',
            'totalHadirHariIni',
            'hari',
            'hadirMingguan',
            'guruAktif',
            'aktivitas'
        ));
    }
}