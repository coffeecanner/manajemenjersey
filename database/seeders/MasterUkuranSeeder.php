<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterUkuranSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['ukuran_baju'=>'S Anak',       'kategori'=>'Anak','harga'=>80000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'M Anak',       'kategori'=>'Anak','harga'=>80000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'L Anak',       'kategori'=>'Anak','harga'=>80000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa S',     'kategori'=>'Dewasa','harga'=>100000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa M',     'kategori'=>'Dewasa','harga'=>100000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa L',     'kategori'=>'Dewasa','harga'=>100000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa XL',    'kategori'=>'Dewasa','harga'=>110000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa XXL',   'kategori'=>'Dewasa','harga'=>110000,'status_aktif'=>1,'tanggal_buat'=>now()],
            ['ukuran_baju'=>'Dewasa XXXL',  'kategori'=>'Dewasa','harga'=>120000,'status_aktif'=>1,'tanggal_buat'=>now()],
        ];

        DB::table('master_ukuran')->upsert(
            $rows, ['ukuran_baju'], ['kategori','harga','status_aktif','tanggal_buat']
        );
    }
}
