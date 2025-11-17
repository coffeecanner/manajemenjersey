<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan_utama', function (Blueprint $table) {
            $table->bigIncrements('id_pesanan');
            $table->string('nama_pemesan', 150);
            $table->string('nomor_whatsapp', 25)->index(); // sering dicari
            $table->integer('nomor_punggung')->nullable();
            $table->integer('total_pesanan')->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);

            // status sesuai scope di model
            $table->enum('status_pesanan', ['pending','confirmed','processing','completed','cancelled'])
                  ->default('pending')->index();

            // sesuai casts di model (meski $timestamps=false, kolom ini tetap kita sediakan)
            $table->dateTime('tanggal_pesan')->nullable();
            $table->dateTime('tanggal_update')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_utama');
    }
};
