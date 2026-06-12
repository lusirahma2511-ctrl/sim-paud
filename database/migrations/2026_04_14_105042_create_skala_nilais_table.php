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
    Schema::create('skala_nilais', function (Blueprint $table) {
        $table->id();
        $table->string('keterangan'); // Contoh: Sangat Baik (SB)
        $table->integer('nilai');      // Contoh: 100
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skala_nilais');
    }
};
