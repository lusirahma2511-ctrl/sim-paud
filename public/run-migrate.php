<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<h1>Jalankan Migration via Browser</h1>";

try {
    // Jalankan migration
    $exitCode = $kernel->call('migrate', ['--force' => true]);
    
    if ($exitCode === 0) {
        echo "<p style='color: green; font-weight: bold;'>✅ Migration berhasil dijalankan!</p>";
        echo "<pre>" . $kernel->output() . "</pre>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Migration gagal!</p>";
        echo "<pre>" . $kernel->output() . "</pre>";
    }
    
    // Clear cache
    echo "<hr><h2>Clear Cache...</h2>";
    $kernel->call('cache:clear');
    $kernel->call('config:clear');
    $kernel->call('route:clear');
    $kernel->call('view:clear');
    echo "<p style='color: green;'>✅ Cache berhasil dibersihkan!</p>";
    
    echo "<hr><h2 style='color: green;'>SELESAI! Silakan coba presensi lagi!</h2>";
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
