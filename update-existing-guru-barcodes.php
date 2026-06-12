<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Guru;

echo "Updating existing guru barcodes...\n";

$gurus = Guru::all();
$updatedCount = 0;

foreach ($gurus as $guru) {
    if ($guru->nip && $guru->nip != '-') {
        $expectedBarcode = $guru->nip;
    } else {
        // For existing gurus without NIP, use "G" + ID
        $expectedBarcode = 'G' . $guru->id;
    }
    
    if ($guru->barcode !== $expectedBarcode) {
        $guru->update(['barcode' => $expectedBarcode]);
        echo "Updated guru ID {$guru->id}: {$guru->nama_guru}\n";
        $updatedCount++;
    }
}

echo "\nDone! Updated {$updatedCount} gurus.\n";
