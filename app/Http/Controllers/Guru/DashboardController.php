<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $guruId = auth()->user()->id; // Asumsi user id adalah guru id atau ada relasi
        
        // Cari data guru
        $guru = \App\Models\Guru::where('user_id', $guruId)->first();
        $actualGuruId = $guru ? $guru->id : null;

        // Presensi guru hari ini
        $presensiGuruHariIni = PresensiGuru::query()
            ->where('tanggal', $today)
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->first();

        // Total presensi siswa hari ini (yang diinput oleh guru ini)
        $presensiSiswaHariIni = PresensiSiswa::query()
            ->where('tanggal', $today)
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->count();

        $hadir = PresensiSiswa::query()
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Hadir', 'hadir'])
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->count();

        $izin = PresensiSiswa::query()
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Izin', 'izin'])
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->count();

        $sakit = PresensiSiswa::query()
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Sakit', 'sakit'])
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->count();

        $alpha = PresensiSiswa::query()
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Alpha', 'alpha'])
            ->when($actualGuruId, function($q) use ($actualGuruId) {
                return $q->where('guru_id', $actualGuruId);
            })
            ->count();

        return view('guru.dashboard', [
            'presensiGuruHariIni' => $presensiGuruHariIni,
            'presensiSiswaHariIni' => $presensiSiswaHariIni,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
        ]);
    }
}