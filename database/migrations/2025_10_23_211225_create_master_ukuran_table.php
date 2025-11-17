<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_ukuran', function (Blueprint $table) {
            $table->bigIncrements('id_ukuran');

            // Field ini dipakai sebagai FK oleh detail_anggota, jadi unik
            $table->string('ukuran_baju', 50)->unique();

            $table->enum('kategori', ['Anak','Dewasa'])->index();
            $table->decimal('harga', 12, 2);
            $table->boolean('status_aktif')->default(true)->index();

            // di model: const CREATED_AT = 'tanggal_buat' (meski $timestamps=false)
            $table->dateTime('tanggal_buat')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_ukuran');
    }
};
