<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom barcode ke tabel gurus
        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'barcode')) {
                $table->string('barcode')->unique()->nullable();
            }
        });

        // Tambah kolom barcode ke tabel siswas
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'barcode')) {
                $table->string('barcode')->unique()->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
    }
};