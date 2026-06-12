<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Siswa;

echo "Updating existing student barcodes...\n";

$siswas = Siswa::all();
$updatedCount = 0;

foreach ($siswas as $siswa) {
    $expectedBarcode = $siswa->nisn !== '-' ? $siswa->nisn : 'SISWA_' . $siswa->id;
    
    if ($siswa->barcode !== $expectedBarcode) {
        $siswa->update(['barcode' => $expectedBarcode]);
        echo "Updated siswa ID {$siswa->id}: {$siswa->nama_siswa}\n";
        $updatedCount++;
    }
}

echo "\nDone! Updated {$updatedCount} students.\n";
