<?php

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SkalaController;
use App\Http\Controllers\ERaporController;
use App\Http\Controllers\OrangtuaController;
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

require __DIR__.'/auth.php';


// Root
Route::get('/', function () {
    return redirect()->route('login');
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
    Route::resource('orang_tua', OrangtuaController::class);
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
Route::middleware(['auth', 'role:guru,guru_kelas'])->prefix('guru')->name('guru.')->group(function () {
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