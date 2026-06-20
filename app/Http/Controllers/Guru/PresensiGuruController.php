<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiGuruController extends Controller
{
    /**
     * Halaman utama presensi
     */
    public function index()
    {
        $today = now()->toDateString();

        // Presensi guru login hari ini
        $presensiGuruHariIni = PresensiGuru::where('guru_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        // Total siswa hadir hari ini
        $presensiSiswaHariIni = PresensiSiswa::whereDate('tanggal', $today)
            ->count();

        return view('guru.presensi.index', compact(
            'presensiGuruHariIni',
            'presensiSiswaHariIni'
        ));
    }

    /**
     * Cek apakah hari ini adalah hari libur (dari pengaturan admin) dan waktu presensi
     */
    private function canScanPresensi()
    {
        $now = now();
        $today = $now->toDateString();
        
        // Cek apakah hari ini libur di database
        $isHariLibur = HariLibur::where('tanggal', $today)->exists();
        if ($isHariLibur) {
            return [
                'success' => false,
                'message' => 'Hari ini adalah hari libur, presensi tidak bisa dilakukan!'
            ];
        }
        
        // Cek waktu: 08:00 - 11:00
        $currentHour = $now->hour;
        if ($currentHour < 8 || $currentHour >= 11) {
            return [
                'success' => false,
                'message' => 'Presensi hanya bisa dilakukan antara jam 08:00 sampai 11:00!'
            ];
        }
        
        return ['success' => true];
    }

    /**
     * Scan barcode guru
     */
    public function scanGuru(Request $request)
    {
        // Cek izin presensi
        $canScan = $this->canScanPresensi();
        if (!$canScan['success']) {
            return response()->json($canScan);
        }

        $barcode = trim($request->barcode);

        $today = now()->toDateString();

        $now = now()->format('H:i:s');

        // Cari guru berdasarkan barcode / nip
        $guru = Guru::where('barcode', $barcode)
            ->orWhere('nip', $barcode)
            ->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan!'
            ]);
        }

        if ($guru->status !== 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Guru dinonaktifkan!'
            ]);
        }

        // Cek presensi hari ini
        $presensi = PresensiGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Kalau sudah presensi
        if ($presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Guru sudah presensi hari ini'
            ]);
        }

        // Simpan presensi masuk
        PresensiGuru::create([

            'guru_id' => $guru->id,

            'tanggal' => $today,

            'jam_masuk' => $now,

            'status' => 'hadir'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Presensi berhasil',

            'data' => [

                'nama' => $guru->nama_guru,

                'jam' => $now

            ]

        ]);
    }

    /**
     * Scan barcode siswa
     */
    public function scanSiswa(Request $request)
    {
        // Cek izin presensi
        $canScan = $this->canScanPresensi();
        if (!$canScan['success']) {
            return response()->json($canScan);
        }

        $barcode = trim($request->barcode);

        $today = now()->toDateString();

        $now = now()->format('H:i:s');

        // Cari siswa berdasarkan barcode / nisn
        $siswa = Siswa::where('barcode', $barcode)
            ->orWhere('nisn', $barcode)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan!'
            ]);
        }

        if ($siswa->status !== 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Siswa dinonaktifkan!'
            ]);
        }

        // Cek sudah presensi?
        $cek = PresensiSiswa::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($cek) {

            return response()->json([
                'success' => false,
                'message' => $siswa->nama_siswa . ' sudah presensi hari ini'
            ]);

        }

        // Simpan presensi siswa
        PresensiSiswa::create([

            'siswa_id' => $siswa->id,

            'kelas_id' => $siswa->kelas_id,

            'tanggal' => $today,

            'jam_masuk' => $now,

            'status' => 'hadir'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Presensi berhasil',

            'data' => [

                'nama' => $siswa->nama_siswa,

                'kelas' => optional($siswa->kelas)->nama_kelas ?? '-',

                'jam' => $now

            ]

        ]);
    }

    /**
     * Riwayat presensi
     */
    public function riwayat(Request $request)
    {
        $tipe = $request->get('tipe', 'guru');

        $bulan = $request->get('bulan', date('m'));

        $tahun = $request->get('tahun', date('Y'));

        // RIWAYAT GURU
        if ($tipe == 'guru') {

            $presensi = PresensiGuru::where('guru_id', Auth::id())
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'desc')
                ->get();

        } else {

            // RIWAYAT SISWA
            $presensi = PresensiSiswa::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->with(['siswa', 'kelas'])
                ->orderBy('tanggal', 'desc')
                ->get();

        }

        return view('guru.presensi.riwayat', compact(
            'presensi',
            'tipe',
            'bulan',
            'tahun'
        ));
    }
}