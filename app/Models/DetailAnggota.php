<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailAnggota extends Model
{
    use HasFactory;

    protected $table = 'detail_anggota';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan',
        'nama_anggota',
        'nama_di_jersey',
        'umur',
        'jenis_kelamin',
        'ukuran_baju',
        'harga_baju',
        'nomor_punggung',
        'urutan_anggota',
    ];

    protected $casts = [
        'harga_baju' => 'decimal:2',
        'umur' => 'integer',
        'nomor_punggung' => 'integer',
        'urutan_anggota' => 'integer',
    ];

    // Disable Laravel's default timestamps
    public $timestamps = false;

    // Relasi dengan pesanan utama
    public function pesananUtama(): BelongsTo
    {
        return $this->belongsTo(PesananUtama::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi dengan master ukuran
    public function masterUkuran(): BelongsTo
    {
        return $this->belongsTo(MasterUkuran::class, 'ukuran_baju', 'ukuran_baju');
    }

    // Scope untuk jenis kelamin
    public function scopeLakiLaki($query)
    {
        return $query->where('jenis_kelamin', 'Laki-laki');
    }

    public function scopePerempuan($query)
    {
        return $query->where('jenis_kelamin', 'Perempuan');
    }

    // Scope untuk kategori umur
    public function scopeAnak($query)
    {
        return $query->where('umur', '<', 18);
    }

    public function scopeDewasa($query)
    {
        return $query->where('umur', '>=', 18);
    }

    // Scope untuk ukuran baju
    public function scopeUkuranBaju($query, $ukuran)
    {
        return $query->where('ukuran_baju', $ukuran);
    }

    // Accessor untuk kategori berdasarkan umur
    public function getKategoriUmurAttribute()
    {
        return $this->umur < 18 ? 'Anak' : 'Dewasa';
    }

    // Accessor untuk format nama dengan gelar
    public function getNamaLengkapAttribute()
    {
        $gelar = $this->jenis_kelamin === 'Laki-laki' ? 'Bapak' : 'Ibu';
        return $gelar . ' ' . $this->nama_anggota;
    }

    // Method untuk mendapatkan harga dari master ukuran
    public function getHargaFromMaster()
    {
        $masterUkuran = MasterUkuran::where('ukuran_baju', $this->ukuran_baju)->first();
        return $masterUkuran ? $masterUkuran->harga : $this->harga_baju;
    }
}
