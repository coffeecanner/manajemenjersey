<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\PenghitunganHargaService;
use Tests\TestCase;

class HitungTotalHargaTest extends TestCase
{
    public function test_array_dengan_tiga_elemen_dijumlahkan_benar(): void
    {
        $hasil = PenghitunganHargaService::hitungTotalHarga([100000, 110000, 120000]);

        $this->assertEquals(330000, $hasil);
    }

    public function test_array_kosong_menghasilkan_nol(): void
    {
        $hasil = PenghitunganHargaService::hitungTotalHarga([]);

        $this->assertEquals(0, $hasil);
    }

    public function test_array_dengan_nilai_besar_tidak_overflow(): void
    {
        $nilaiBesar = PHP_INT_MAX - 1;

        $hasil = PenghitunganHargaService::hitungTotalHarga([$nilaiBesar, 1]);

        $this->assertEquals(PHP_INT_MAX, $hasil);
    }

    public function test_array_dengan_nilai_desimal_dijumlahkan_presisi(): void
    {
        $hasil = PenghitunganHargaService::hitungTotalHarga([80000.5, 120000.25, 5000.25]);

        $this->assertEqualsWithDelta(205001.0, $hasil, 0.0001);
    }
}
