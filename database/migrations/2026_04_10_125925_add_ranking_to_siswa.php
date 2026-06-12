<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('siswas', function (Blueprint $table) {

        if (!Schema::hasColumn('siswas', 'ranking')) {
            $table->integer('ranking')->nullable();
        }

        if (!Schema::hasColumn('siswas', 'hasil_saw')) {
            $table->double('hasil_saw')->nullable();
        }

    });
}

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['ranking', 'hasil_saw']);
        });
    }
};