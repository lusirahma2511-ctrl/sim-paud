<?php

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SkalaController;
use App\Http\Controllers\ERaporController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\PresensiGuruController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Kepala\DashboardController as KepalaDashboardController;
use App\Http\Controllers\OrangTua\DashboardController as OrangTuaDashboardController;
use App\Http\Controllers\OrangTua\RaporController as OrangTuaRaporController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// TEMPORARY: Create admin account if not exists
Route::get('/create-admin', function () {
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@paud.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);
        return "Admin account created successfully! Username: admin, Password: admin123";
    }
    return "Admin account already exists! Username: admin";
});

// TEMPORARY: Debug and fix users table status column
Route::get('/debug-users-table', function () {
    try {
        $message = "<h3>Debug Users Table</h3>";
        
        // Get all columns in users table
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        $message .= "<p><strong>Columns in users table:</strong> " . implode(', ', $columns) . "</p>";
        
        // Check if status column exists
        $hasStatusColumn = in_array('status', $columns);
        $message .= "<p><strong>Has status column:</strong> " . ($hasStatusColumn ? 'YES' : 'NO') . "</p>";
        
        if (!$hasStatusColumn) {
            $message .= "<p>Adding status column...</p>";
            \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif')->after('role');
            });
            $message .= "<p>Status column added successfully!</p>";
        }
        
        // Update ALL users except admin? No, update all, then ensure admin is Aktif
        $updated = \Illuminate\Support\Facades\DB::table('users')->update(['status' => 'Aktif']);
        $message .= "<p>Updated ALL {$updated} users to have status 'Aktif' (using DB facade)</p>";
        
        // Ensure admin is always Aktif
        \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'admin')
            ->update(['status' => 'Aktif']);
        $message .= "<p>Ensured admin user is always Aktif!</p>";
        
        // Show raw users from DB
        $users = \Illuminate\Support\Facades\DB::table('users')->select('id', 'name', 'role', 'status')->get();
        $message .= "<h4>Raw Users from DB:</h4><ul>";
        foreach ($users as $user) {
            $message .= "<li>ID: {$user->id} | Name: {$user->name} | Role: {$user->role} | Status: " . ($user->status ?? 'NULL') . " | Type: " . gettype($user->status) . "</li>";
        }
        $message .= "</ul>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMPORARY: Set user status to Nonaktif (for testing)
Route::get('/set-user-nonaktif/{id}', function ($id) {
    try {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['status' => 'Nonaktif']);
        return "User {$user->name} (ID: {$user->id}) status set to Nonaktif!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// TEMPORARY: Set user status to Aktif (for testing)
Route::get('/set-user-aktif/{id}', function ($id) {
    try {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['status' => 'Aktif']);
        return "User {$user->name} (ID: {$user->id}) status set to Aktif!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// TEMPORARY: Check admin user
Route::get('/check-admin', function () {
    try {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) {
            return "No admin user found!";
        }
        return "Admin found! ID: {$admin->id} | Name: {$admin->name} | Username: {$admin->username} | Email: {$admin->email} | Status: " . ($admin->status ?? 'NOT SET');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// TEMPORARY: Set admin to Aktif
Route::get('/set-admin-aktif', function () {
    try {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) {
            return "No admin user found!";
        }
        $admin->update(['status' => 'Aktif']);
        return "Admin {$admin->name} status set to Aktif!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// TEMPORARY: Drop jam_pulang column from presensi_gurus
Route::get('/drop-jam-pulang-presensi-guru', function () {
    try {
        $message = "<h3>Drop Jam Pulang Column from Presensi Gurus</h3>";
        
        // Check if column exists
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('presensi_gurus');
        $message .= "<p>Columns in presensi_gurus: " . implode(', ', $columns) . "</p>";
        
        if (in_array('jam_pulang', $columns)) {
            $message .= "<p>Dropping jam_pulang column...</p>";
            \Illuminate\Support\Facades\Schema::table('presensi_gurus', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->dropColumn('jam_pulang');
            });
            $message .= "<p>jam_pulang column dropped successfully!</p>";
        } else {
            $message .= "<p>jam_pulang column doesn't exist, skipping.</p>";
        }
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMPORARY: Update all presensi status to lowercase
Route::get('/fix-presensi-status', function () {
    try {
        $message = "<h3>Fix Presensi Status</h3>";
        
        // Update PresensiGuru
        $guruUpdated = 0;
        $presensiGuru = \App\Models\PresensiGuru::all();
        foreach ($presensiGuru as $p) {
            if ($p->status !== strtolower($p->status)) {
                $p->update(['status' => strtolower($p->status)]);
                $guruUpdated++;
            }
        }
        
        // Update PresensiSiswa
        $siswaUpdated = 0;
        $presensiSiswa = \App\Models\PresensiSiswa::all();
        foreach ($presensiSiswa as $p) {
            if ($p->status !== strtolower($p->status)) {
                $p->update(['status' => strtolower($p->status)]);
                $siswaUpdated++;
            }
        }
        
        $message .= "<p>Presensi Guru updated: $guruUpdated</p>";
        $message .= "<p>Presensi Siswa updated: $siswaUpdated</p>";
        $message .= "<p>Total: " . ($guruUpdated + $siswaUpdated) . "</p>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMPORARY: Debug Kelas and Siswa relation
Route::get('/debug-kelas-siswa', function () {
    try {
        $message = "<h3>Debug Kelas & Siswa</h3>";
        
        // Get all kelas with siswa
        $kelasList = \App\Models\Kelas::with('siswa')->get();
        
        foreach ($kelasList as $k) {
            $message .= "<h4>Kelas: " . $k->nama_kelas . " (ID: " . $k->id . ")</h4>";
            $message .= "<p>Jumlah siswa: " . $k->siswa->count() . "</p>";
            
            if ($k->siswa->count() > 0) {
                $message .= "<ul>";
                foreach ($k->siswa as $s) {
                    $message .= "<li>" . $s->nama_siswa . " (kelas_id: " . $s->kelas_id . ")</li>";
                }
                $message .= "</ul>";
            }
        }
        
        $message .= "<hr><h4>All Siswa:</h4><ul>";
        $allSiswa = \App\Models\Siswa::all();
        foreach ($allSiswa as $s) {
            $message .= "<li>" . $s->nama_siswa . " (kelas_id: " . $s->kelas_id . ")</li>";
        }
        $message .= "</ul>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

Route::get('/debug-kelas-siswa/{id}', function ($id) {
    try {
        $message = "<h3>Debug Detail Kelas ID: $id</h3>";
        
        $kelas = \App\Models\Kelas::with('siswa')->find($id);
        if (!$kelas) {
            return "<p>Kelas dengan ID $id tidak ditemukan!</p>";
        }
        
        $message .= "<h4>Kelas: " . $kelas->nama_kelas . "</h4>";
        $message .= "<p>Jumlah siswa: " . $kelas->siswa->count() . "</p>";
        
        if ($kelas->siswa->count() > 0) {
            $message .= "<ul>";
            foreach ($kelas->siswa as $s) {
                $message .= "<li>" . $s->nama_siswa . " (kelas_id: " . $s->kelas_id . ", ID: " . $s->id . ", status: " . ($s->status ?? 'NULL') . ")</li>";
            }
            $message .= "</ul>";
        }
        
        // Check Siswa where kelas_id = $id directly
        $siswaByQuery = \App\Models\Siswa::where('kelas_id', $id)->get();
        $message .= "<hr><h4>Siswa dari Query where kelas_id = $id: " . $siswaByQuery->count() . "</h4>";
        foreach ($siswaByQuery as $s) {
            $message .= "<li>" . $s->nama_siswa . " (ID: " . $s->id . ", kelas_id: " . $s->kelas_id . ", status: " . ($s->status ?? 'NULL') . ")</li>";
        }
        
        // Cek struktur tabel siswa
        $message .= "<hr><h4>Struktur Tabel Siswa:</h4>";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('siswas');
        $message .= "<p>Columns: " . implode(', ', $columns) . "</p>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMPORARY: Add default value to users.status column
Route::get('/fix-users-status-default', function () {
    try {
        $message = "<h3>Fix Users Status Default Value</h3>";
        
        // Check if status column exists
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        if (!in_array('status', $columns)) {
            return "<p>Status column doesn't exist!</p>";
        }
        
        // Modify column to have default value 'Aktif'
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif'");
        
        $message .= "<p>Successfully added default value 'Aktif' to users.status column!</p>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMPORARY: Add status column to siswas if not exists
Route::get('/fix-siswa-status-column', function () {
    try {
        $message = "<h3>Fix Siswa Status Column</h3>";
        
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('siswas');
        if (!in_array('status', $columns)) {
            \Illuminate\Support\Facades\Schema::table('siswas', function ($table) {
                $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif')->after('password');
            });
            $message .= "<p>Status column added successfully!</p>";
        } else {
            $message .= "<p>Status column already exists!</p>";
        }
        
        // Set default for existing records
        \App\Models\Siswa::whereNull('status')->update(['status' => 'Aktif']);
        $message .= "<p>Existing records updated!</p>";
        
        return $message;
    } catch (\Exception $e) {
        return "<h3>Error</h3><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

require __DIR__.'/auth.php';


// Clear Cache (Temporary)
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache cleared successfully!";
});

// Root
Route::get('/', function () {
    return redirect()->route('login');
});

// Clear semua cache (config, route, view, cache)
Route::get('/clear-all-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "Semua cache berhasil dibersihkan!";
});

// Tambah kolom semester dan tahun_ajaran ke tabel presensi_siswas
Route::get('/add-presensi-columns', function () {
    try {
        if (!Schema::hasColumn('presensi_siswas', 'semester')) {
            Schema::table('presensi_siswas', function (Blueprint $table) {
                $table->integer('semester')->default(1)->after('keterangan');
            });
            echo "✅ Kolom semester berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Kolom semester sudah ada<br>";
        }

        if (!Schema::hasColumn('presensi_siswas', 'tahun_ajaran')) {
            Schema::table('presensi_siswas', function (Blueprint $table) {
                $table->string('tahun_ajaran')->nullable()->after('semester');
            });
            echo "✅ Kolom tahun_ajaran berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Kolom tahun_ajaran sudah ada<br>";
        }

        echo "<br>🎉 Selesai!";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
});

// Update data presensi lama (isi semester dan tahun_ajaran)
Route::get('/update-presensi-lama', function () {
    try {
        $presensiList = \App\Models\PresensiSiswa::whereNull('semester')->orWhereNull('tahun_ajaran')->get();
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

        echo "✅ Berhasil update {$count} data presensi lama!";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
});

// Debug data user dan guru
Route::get('/debug-users-guru', function () {
    $users = \App\Models\User::whereIn('role', ['guru', 'guru_kelas'])->get();
    $gurus = \App\Models\Guru::all();
    
    echo "<h3>Data Users (Role Guru/Guru Kelas):</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Guru ID</th></tr>";
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>{$u->id}</td>";
        echo "<td>{$u->username}</td>";
        echo "<td>{$u->email}</td>";
        echo "<td>{$u->role}</td>";
        echo "<td>" . ($u->status ?? 'N/A') . "</td>";
        echo "<td>{$u->guru_id}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>Data Guru:</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nama Guru</th><th>NIP</th><th>Status</th></tr>";
    foreach ($gurus as $g) {
        echo "<tr>";
        echo "<td>{$g->id}</td>";
        echo "<td>{$g->nama_guru}</td>";
        echo "<td>{$g->nip}</td>";
        echo "<td>{$g->status}</td>";
        echo "</tr>";
    }
    echo "</table>";
});

// Debug route untuk cek data orang tua
Route::get('/debug-orang-tua/{id?}', function ($id = null) {
    try {
        if ($id) {
            $ortu = \App\Models\OrangTua::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $ortu
            ]);
        } else {
            $ortu = \App\Models\OrangTua::all();
            return response()->json([
                'status' => 'success',
                'count' => count($ortu),
                'data' => $ortu
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});

// TEMPORARY: Create storage symlink manually (for hosting)
Route::get('/create-storage-symlink', function () {
    try {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        
        if (file_exists($publicPath)) {
            return "Symlink already exists!";
        }
        
        // Create symlink
        symlink($storagePath, $publicPath);
        
        return "Symlink created successfully!";
    } catch (\Exception $e) {
        return "Error creating symlink: " . $e->getMessage();
    }
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// Dashboard
Route::get('/dashboard', function () {
    if (Auth::check()) {
        switch (Auth::user()->role) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'guru': return redirect()->route('guru.dashboard');
            case 'kepala_sekolah': return redirect()->route('kepala.dashboard');
            case 'orang_tua': return redirect()->route('orangtua.dashboard');
            default: Auth::logout(); return redirect('/login');
        }
    }
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

// ===================== ADMIN =====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Semua management routes admin
    Route::resource('users', UserController::class);
    Route::get('/users/reset-password/{id}', [UserController::class, 'resetPassword'])
    ->name('users.resetPassword');
    Route::resource('siswa', SiswaController::class);
    Route::resource('orang_tua', OrangTuaController::class);
    Route::get('/siswa/kartu/{id}', [SiswaController::class,'kartu'])->name('siswa.kartu');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('guru', GuruController::class); // Guru management
    Route::get('guru-export', [GuruController::class, 'export'])->name('guru.export');
    Route::resource('kelas', KelasController::class);
    Route::resource('hari_libur', HariLiburController::class)->parameters(['hari_libur' => 'hariLibur'])->except(['create', 'edit']);

    Route::resource('penilaian', PenilaianController::class)
    ->parameters(['penilaian' => 'kriteria']);
    Route::get('penilaian-seed', [PenilaianController::class, 'seed'])
    ->name('penilaian.seed');
    Route::resource('skala', SkalaController::class)->except(['show'])->parameters(['skala' => 'skala']);
    
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
Route::put('/presensi/{id}', [PresensiController::class, 'update'])->name('presensi.update');
Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.destroy');
Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');

/* TAMBAH INI (INI YANG KURANG!) */
Route::get('/presensi/{id}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');



    Route::get('/erapor', [ERaporController::class, 'index'])->name('erapor.index');
    Route::get('/erapor/siswa', [ERaporController::class, 'getSiswaByKelas'])->name('erapor.siswa');
    Route::get('/erapor/{siswa}', [ERaporController::class, 'show'])->name('erapor.show');
    Route::get('/erapor/{siswa}/print', [ERaporController::class, 'print'])->name('erapor.print');
    Route::get('/erapor/download/{siswa}', [ERaporController::class, 'download'])->name('erapor.download');
   // ================= LAPORAN =================

// khusus
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

Route::get('/laporan/presensi-siswa', [LaporanController::class, 'presensiSiswa'])->name('laporan.presensiSiswa');

Route::get('/laporan/presensi-guru', [LaporanController::class, 'presensiGuru'])->name('laporan.presensiGuru');

Route::get('/laporan/penilaian', [LaporanController::class, 'penilaian'])->name('laporan.penilaian');

Route::get('/laporan/presensi-siswa/cetak', [LaporanController::class, 'cetakPresensiSiswa'])->name('laporan.presensiSiswa.cetak');
Route::get('/laporan/presensi-siswa/download', [LaporanController::class, 'downloadPresensiSiswa'])
    ->name('laporan.presensiSiswa.download');
Route::get('/laporan/presensi-guru/cetak', [LaporanController::class, 'cetakPresensiGuru'])->name('laporan.presensiGuru.cetak');
Route::get('/laporan/presensi-guru/download', [LaporanController::class, 'downloadPresensiGuru'])
    ->name('laporan.presensiGuru.download');

Route::get('/laporan/penilaian/cetak', [LaporanController::class, 'cetakPenilaian'])->name('laporan.penilaian.cetak');
Route::get('/laporan/penilaian/download', [LaporanController::class, 'downloadPenilaian'])
    ->name('laporan.penilaian.download');


// GENERIC TARUH PALING BAWAH
Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
});
// ===================== GURU (Prefix: /guru) =====================
// Ini JANGAN pakai prefix 'admin' karena prefix-nya sudah '/guru'
Route::middleware(['auth', 'role:guru,guru_kelas,guru_pendamping'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/presensi', [PresensiGuruController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/riwayat', [PresensiGuruController::class, 'riwayat'])->name('presensi.riwayat');
    Route::post('/presensi/scan-guru', [PresensiGuruController::class, 'scanGuru'])->name('presensi.scanGuru');
    Route::post('/presensi/scan-siswa', [PresensiGuruController::class, 'scanSiswa'])->name('presensi.scanSiswa');
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/get-siswa', [NilaiController::class, 'getSiswa'])->name('nilai.getSiswa');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/riwayat', [NilaiController::class, 'riwayat'])->name('nilai.riwayat');
    Route::delete('/nilai/hapus/{siswa_id}', [App\Http\Controllers\Guru\NilaiController::class, 'destroy'])->name('nilai.destroy');


});

    Route::prefix('kepala')->middleware(['auth','role:kepala_sekolah'])->group(function(){

    Route::get('/dashboard', [KepalaDashboardController::class,'index'])
        ->name('kepala.dashboard');

    Route::get('/presensi-siswa', [KepalaDashboardController::class,'presensiSiswa'])
        ->name('kepala.presensiSiswa');

    Route::get('/presensi-guru', [KepalaDashboardController::class,'presensiGuru'])
        ->name('kepala.presensiGuru');

    Route::get('/penilaian', [KepalaDashboardController::class,'penilaian'])
        ->name('kepala.penilaian');

});

   Route::prefix('orangtua')->middleware(['auth','role:orang_tua'])->group(function () {

    Route::get('/dashboard', [OrangTuaDashboardController::class,'index'])
        ->name('orangtua.dashboard');

    Route::get('/rapor', [OrangTuaDashboardController::class,'raporIndex'])  
        ->name('orangtua.rapor');
    Route::get('/rapor/{siswa_id}', [OrangTuaDashboardController::class,'raporShow'])  
        ->name('orangtua.rapor.show');

});

// TEMPORARY: Update Tabel Presensi Siswa
Route::get('/update-presensi-table', function () {
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
});

// TEMPORARY: Add Columns Direct SQL
Route::get('/add-columns-sql', function () {
    echo "<h1>Tambah Kolom dengan SQL Langsung</h1>";
    
    try {
        // Cek dan tambah kolom semester
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM presensi_siswas LIKE 'semester'");
        if (empty($columns)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE presensi_siswas ADD COLUMN semester VARCHAR(255) NULL AFTER status");
            echo "<p style='color: green;'>✅ Kolom `semester` berhasil ditambahkan!</p>";
        } else {
            echo "<p style='color: blue;'>ℹ️ Kolom `semester` sudah ada!</p>";
        }
        
        // Cek dan tambah kolom tahun_ajaran
        $columns2 = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM presensi_siswas LIKE 'tahun_ajaran'");
        if (empty($columns2)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE presensi_siswas ADD COLUMN tahun_ajaran VARCHAR(255) NULL AFTER semester");
            echo "<p style='color: green;'>✅ Kolom `tahun_ajaran` berhasil ditambahkan!</p>";
        } else {
            echo "<p style='color: blue;'>ℹ️ Kolom `tahun_ajaran` sudah ada!</p>";
        }
        
        echo "<hr><h2>Selesai!</h2>";
    } catch (\Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
    }
});

// TEMPORARY: Run Migration
Route::get('/run-migration', function () {
    echo "<h1>Jalankan Migration</h1>";
    
    try {
        Artisan::call('migrate');
        echo "<p style='color: green; font-weight: bold;'>Migration berhasil dijalankan!</p>";
        echo "<pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
    }
});

// TEMPORARY: Debug Presensi
Route::get('/debug-presensi', function () {
    echo "<h1>Debug Presensi</h1>";

    // Cek kolom presensi_siswas:
    $columns = Schema::getColumnListing('presensi_siswas');
    echo "<h3>Kolom di tabel presensi_siswas:</h3><ul>";
    foreach ($columns as $col) {
        echo "<li>{$col}</li>";
    }
    echo "</ul>";

    // Cek 10 data presensi terakhir:
    $presensi = \App\Models\PresensiSiswa::latest()->take(10)->get();
    echo "<h3>10 Data Presensi Terakhir:</h3>";
    if ($presensi->isEmpty()) {
        echo "<p>Belum ada data presensi.</p>";
    } else {
        echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Siswa</th><th>Tanggal</th><th>Semester</th><th>Tahun Ajaran</th></tr>";
        foreach ($presensi as $p) {
            echo "<tr><td>{$p->id}</td><td>".($p->siswa->nama_siswa ?? '?')."</td><td>{$p->tanggal}</td><td>{$p->semester}</td><td>{$p->tahun_ajaran}</td></tr>";
        }
        echo "</table>";
    }
});

// TEMPORARY: Test Scan Route
Route::post('/test-scan', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test route berfungsi!',
        'data' => $request->all()
    ]);
});

// TEMPORARY: Hapus semua presensi hari ini
Route::get('/clear-presensi-hari-ini', function () {
    echo "<h1>Hapus Presensi Hari Ini</h1>";
    
    $today = \Illuminate\Support\Carbon::now()->toDateString();
    
    // Hapus presensi siswa hari ini
    $countSiswa = \App\Models\PresensiSiswa::whereDate('tanggal', $today)->delete();
    echo "<p>Menghapus {$countSiswa} data presensi siswa hari ini...</p>";
    
    // Hapus presensi guru hari ini
    $countGuru = \App\Models\PresensiGuru::whereDate('tanggal', $today)->delete();
    echo "<p>Menghapus {$countGuru} data presensi guru hari ini...</p>";
    
    echo "<hr><h2 style='color: green;'>SELESAI!</h2>";
});