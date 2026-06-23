<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('presensi_siswas', 'semester')) {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            $table->integer('semester')->default(1)->after('keterangan');
        });
        echo "✅ Kolom semester berhasil ditambahkan\n";
    } else {
        echo "ℹ️ Kolom semester sudah ada\n";
    }

    if (!Schema::hasColumn('presensi_siswas', 'tahun_ajaran')) {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('semester');
        });
        echo "✅ Kolom tahun_ajaran berhasil ditambahkan\n";
    } else {
        echo "ℹ️ Kolom tahun_ajaran sudah ada\n";
    }

    echo "\n🎉 Selesai!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}