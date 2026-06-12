<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nilai_perkembangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('kriteria_id');
            $table->double('nilai'); // nilai asli (0-100)
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('guru_id'); // guru yang menilai
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('kriteria_id')->references('id')->on('kriterias')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_perkembangans');
    }
};