<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<h1>Update Tabel Presensi Siswa</h1>";

// Cek dan tambah kolom semester
if (!Schema::hasColumn('presensi_siswas', 'semester')) {
    Schema::table('presensi_siswas', function (Blueprint $table) {
        $table->string('semester')->nullable()->after('status');
    });
    echo "<p>✅ Kolom `semester` berhasil ditambahkan!</p>";
} else {
    echo "<p>ℹ️ Kolom `semester` sudah ada!</p>";
}

// Cek dan tambah kolom tahun_ajaran
if (!Schema::hasColumn('presensi_siswas', 'tahun_ajaran')) {
    Schema::table('presensi_siswas', function (Blueprint $table) {
        $table->string('tahun_ajaran')->nullable()->after('semester');
    });
    echo "<p>✅ Kolom `tahun_ajaran` berhasil ditambahkan!</p>";
} else {
    echo "<p>ℹ️ Kolom `tahun_ajaran` sudah ada!</p>";
}

echo "<hr><h2>Selesai! Silakan coba fitur presensi kembali.</h2>";
?>