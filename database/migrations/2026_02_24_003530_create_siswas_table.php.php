<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
    $table->id();

    $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();

    $table->string('nama_siswa');
    $table->string('nisn')->nullable();
    $table->enum('jenis_kelamin', ['L','P']);
    $table->string('tempat_lahir')->nullable();
    $table->date('tanggal_lahir');
    $table->string('agama')->nullable();

    $table->integer('anak_ke')->nullable();
    $table->integer('jumlah_saudara')->nullable();

    $table->text('alamat')->nullable();
    $table->string('barcode')->nullable();
    $table->string('foto')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
