<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesanan_utama', function (Blueprint $table) {
            $table->text('style_request')->nullable()->after('status_pesanan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_utama', function (Blueprint $table) {
            $table->dropColumn('style_request');
        });
    }
};

