<?php

namespace App\Services;

/**
 * Utility sederhana untuk menghitung total harga pesanan.
 * Dipisahkan agar mudah dilakukan white box / unit testing.
 */
class PenghitunganHargaService
{
    /**
     * Hitung total harga dari array angka.
     *
     * @param  array<int, int|float>  $hargaPerAnggota
     * @return int|float
     */
    public static function hitungTotalHarga(array $hargaPerAnggota)
    {
        $total = 0;
        foreach ($hargaPerAnggota as $harga) {
            $total += $harga;
        }

        return $total;
    }
}
