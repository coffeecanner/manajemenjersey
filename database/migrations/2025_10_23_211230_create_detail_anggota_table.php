<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_anggota', function (Blueprint $table) {
            $table->bigIncrements('id_detail');

            // FK ke pesanan_utama
            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')
                  ->references('id_pesanan')->on('pesanan_utama')
                  ->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('nama_anggota', 150);
            $table->integer('umur');
            $table->enum('jenis_kelamin', ['Laki-laki','Perempuan'])->index();

            // FK string ke master_ukuran.ukuran_baju
            $table->string('ukuran_baju', 50);
            $table->foreign('ukuran_baju')
                  ->references('ukuran_baju')->on('master_ukuran')
                  ->cascadeOnUpdate()   // update ukuran di master ikut kebawa
                  ->restrictOnDelete(); // cegah hapus master jika masih dipakai

            $table->decimal('harga_baju', 12, 2)->nullable(); // model sediakan fallback dari master
            $table->integer('nomor_punggung')->nullable();
            $table->integer('urutan_anggota')->nullable();

            // tidak ada timestamps (sesuai $timestamps=false)
            // index tambahan yang sering dipakai filter/sort
            $table->index(['id_pesanan', 'urutan_anggota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_anggota');
    }
};
