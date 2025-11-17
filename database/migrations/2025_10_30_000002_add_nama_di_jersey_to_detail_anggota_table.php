<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_anggota', function (Blueprint $table) {
            $table->string('nama_di_jersey', 30)->nullable()->after('nama_anggota');
        });
    }

    public function down(): void
    {
        Schema::table('detail_anggota', function (Blueprint $table) {
            $table->dropColumn('nama_di_jersey');
        });
    }
};

