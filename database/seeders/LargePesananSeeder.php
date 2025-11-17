<?php

namespace Database\Seeders;

use App\Models\DetailAnggota;
use App\Models\MasterUkuran;
use App\Models\PesananUtama;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargePesananSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Ensure master ukuran available
        $ukuranAktif = MasterUkuran::where('status_aktif', true)->get(['ukuran_baju','harga','kategori']);
        if ($ukuranAktif->isEmpty()) {
            $this->command?->warn('Master ukuran kosong. Menjalankan MasterUkuranSeeder terlebih dahulu.');
            $this->call(MasterUkuranSeeder::class);
            $ukuranAktif = MasterUkuran::where('status_aktif', true)->get(['ukuran_baju','harga','kategori']);
        }

        $ukuranList = $ukuranAktif->map(function($u){ return [
            'nama' => $u->ukuran_baju,
            'harga' => (int)$u->harga,
            'kategori' => $u->kategori,
        ]; })->values()->all();

        if (empty($ukuranList)) {
            $this->command?->error('Tidak ada ukuran aktif, seeding dibatalkan.');
            return;
        }

        // Fixed to 50 orders as requested
        $totalOrders = 50;
        $statuses = ['pending','confirmed','processing','completed','cancelled'];

        // Build a large unique style pool (Serie A + national teams)
        $serieA = [
            'Juventus','AC Milan','Inter Milan','Napoli','AS Roma','Lazio','Atalanta','Fiorentina','Torino','Bologna',
            'Udinese','Sassuolo','Genoa','Lecce','Monza','Empoli','Hellas Verona','Cagliari','Frosinone','Salernitana'
        ];
        $national = [
            'Timnas Belanda','Timnas Italia','Timnas Prancis','Timnas Jerman','Timnas Argentina','Timnas Spanyol'
        ];
        $variants = ['Home','Away','Third'];
        $patterns = ['strip tipis','polos elegan','motif chevron','retro 90-an','gradient halus','lengan raglan'];
        $colorCombos = [
            'hitam putih','merah hitam','biru navy putih','oranye navy','biru langit kuning','hijau neon hitam',
            'ungu emas','merah marun putih','kuning biru','hijau tua putih'
        ];
        $stylePool = [];
        foreach ($serieA as $club) {
            foreach ($variants as $v) {
                $stylePool[] = sprintf('%s %s: warna %s, pola %s, font block tebal', $club, $v, $colorCombos[array_rand($colorCombos)], $patterns[array_rand($patterns)]);
            }
        }
        foreach ($national as $team) {
            foreach ($variants as $v) {
                $stylePool[] = sprintf('%s %s: kombinasi %s, aksen %s', $team, $v, $colorCombos[array_rand($colorCombos)], $patterns[array_rand($patterns)]);
            }
        }
        shuffle($stylePool);
        // Ensure we have enough unique styles
        $styleList = array_slice($stylePool, 0, $totalOrders);

        $bar = $this->command?->getOutput();
        DB::disableQueryLog();

        for ($i = 0; $i < $totalOrders; $i++) {
            $styleReqFixed = $styleList[$i] ?? ('Desain generik #' . ($i+1));
            DB::transaction(function() use ($faker, $ukuranList, $statuses, $styleReqFixed) {
                // Random time in last 6 months
                $tanggalPesan = Carbon::now()->subDays(random_int(0, 180))->addMinutes(random_int(0, 1380));

                $namaPemesan = $faker->name();
                $nomorWhatsapp = $this->makeIndoPhone($faker);
                $nomorPunggung = random_int(1, 99);
                $namaPunggung = $faker->boolean(60) ? strtoupper($faker->firstName()) : null;
                $status = $statuses[array_rand($statuses)];
                $jumlahAnggota = random_int(2, 6);
                $styleReq = $styleReqFixed; // unique per order

                // Hitung total harga berdasarkan anggota
                $totalHarga = 0;

                $pesanan = PesananUtama::create([
                    'nama_pemesan'   => $namaPemesan,
                    'nomor_whatsapp' => $nomorWhatsapp,
                    'nomor_punggung' => $nomorPunggung,
                    'nama_punggung'  => $namaPunggung,
                    'total_pesanan'  => $jumlahAnggota,
                    'total_harga'    => 0, // fill after details
                    'status_pesanan' => $status,
                    'style_request'  => $styleReq,
                    'tanggal_pesan'  => $tanggalPesan,
                    'tanggal_update' => $tanggalPesan,
                ]);

                for ($n = 0; $n < $jumlahAnggota; $n++) {
                    $uk = $ukuranList[array_rand($ukuranList)];
                    $harga = (int) $uk['harga'];
                    $totalHarga += $harga;

                    $namaAnggota = $faker->name();
                    $namaCetak = $faker->boolean(40) ? strtoupper($faker->firstName()) : null;
                    $umur = random_int(6, 55);
                    $jk = $faker->boolean(50) ? 'Laki-laki' : 'Perempuan';

                    DetailAnggota::create([
                        'id_pesanan'     => $pesanan->id_pesanan,
                        'nama_anggota'   => $namaAnggota,
                        'nama_di_jersey' => $namaCetak,
                        'umur'           => $umur,
                        'jenis_kelamin'  => $jk,
                        'ukuran_baju'    => $uk['nama'],
                        'harga_baju'     => $harga,
                        'nomor_punggung' => $pesanan->nomor_punggung,
                        'urutan_anggota' => $n + 1,
                    ]);
                }

                // Update total harga
                $pesanan->update([
                    'total_harga' => $totalHarga,
                    'tanggal_update' => $tanggalPesan,
                ]);
            });

            if ($bar) {
                if (($i+1) % 50 === 0) {
                    $bar->writeln("Seeded pesanan: ".($i+1));
                }
            }
        }
    }

    private function makeIndoPhone($faker): string
    {
        // 08XXXXXXXXXX (10-13 digits total)
        $len = random_int(10, 13);
        $digits = '08';
        for ($i=0; $i<$len-2; $i++) { $digits .= random_int(0,9); }
        return $digits;
    }
}
