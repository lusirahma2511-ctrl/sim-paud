<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $db = DB::connection();
    
    // Check if column exists
    $columns = $db->select("SHOW COLUMNS FROM nilai_perkembangans LIKE 'semester'");
    
    if (empty($columns)) {
        $db->statement("ALTER TABLE nilai_perkembangans ADD COLUMN semester INT(1) DEFAULT 1 AFTER catatan");
        echo "Successfully added semester column to nilai_perkembangans table!\n";
    } else {
        echo "semester column already exists in nilai_perkembangans table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
