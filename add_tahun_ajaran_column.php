<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('nilai_perkembangans', 'tahun_ajaran')) {
        Schema::table('nilai_perkembangans', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('semester');
        });
        echo "✅ Kolom 'tahun_ajaran' berhasil ditambahkan ke tabel 'nilai_perkembangans'!\n";
    } else {
        echo "ℹ️ Kolom 'tahun_ajaran' sudah ada di tabel 'nilai_perkembangans'!\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
