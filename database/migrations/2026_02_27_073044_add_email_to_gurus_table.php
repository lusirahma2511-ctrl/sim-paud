<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'email')) {
                $table->string('email')->nullable()->after('nama');
            }
        });
    }

    public function down()
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};