<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('presensi_siswas', 'semester')) {
                $table->string('semester')->nullable()->after('status');
            }
            if (!Schema::hasColumn('presensi_siswas', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')->nullable()->after('semester');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajaran']);
        });
    }
};
