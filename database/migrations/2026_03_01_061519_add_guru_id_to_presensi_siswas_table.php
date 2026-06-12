<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('presensi_siswas', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
        });
    }
};
