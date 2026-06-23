<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PresensiSiswa;

try {
    $presensiList = PresensiSiswa::whereNull('semester')->orWhereNull('tahun_ajaran')->get();
    $count = 0;

    foreach ($presensiList as $presensi) {
        $bulan = date('n', strtotime($presensi->tanggal));
        $tahun = date('Y', strtotime($presensi->tanggal));
        
        if ($bulan >= 7) {
            $semester = 1;
            $tahunAjaran = $tahun . '/' . ($tahun + 1);
        } else {
            $semester = 2;
            $tahunAjaran = ($tahun - 1) . '/' . $tahun;
        }

        $presensi->update([
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
        ]);
        $count++;
    }

    echo "✅ Berhasil update {$count} data presensi!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}