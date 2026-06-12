<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Tambahkan kolom guru_id jika belum ada
            if (!Schema::hasColumn('kelas', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->after('nama_kelas')->constrained('gurus')->onDelete('set null');
            }
            
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('kelas', 'gurukelas')) {
                $table->dropColumn('gurukelas');
            }
            if (Schema::hasColumn('kelas', 'walikelas')) {
                $table->dropColumn('walikelas');
            }
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
            $table->string('gurukelas')->nullable();
        });
    }
};
