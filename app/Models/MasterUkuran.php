<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterUkuran extends Model
{
    use HasFactory;

    protected $table = 'master_ukuran';
    protected $primaryKey = 'id_ukuran';

    protected $fillable = [
        'ukuran_baju',
        'kategori',
        'harga',
        'status_aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'status_aktif' => 'boolean',
        'tanggal_buat' => 'datetime',
    ];

    // Disable Laravel's default timestamps
    public $timestamps = false;

    // Custom timestamp columns
    const CREATED_AT = 'tanggal_buat';

    // Relasi dengan detail anggota
    public function detailAnggota(): HasMany
    {
        return $this->hasMany(DetailAnggota::class, 'ukuran_baju', 'ukuran_baju');
    }

    // Scope untuk kategori
    public function scopeAnak($query)
    {
        return $query->where('kategori', 'Anak');
    }

    public function scopeDewasa($query)
    {
        return $query->where('kategori', 'Dewasa');
    }

    // Scope untuk status aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeTidakAktif($query)
    {
        return $query->where('status_aktif', false);
    }

    // Scope untuk rentang harga
    public function scopeHargaMinimal($query, $harga)
    {
        return $query->where('harga', '>=', $harga);
    }

    public function scopeHargaMaksimal($query, $harga)
    {
        return $query->where('harga', '<=', $harga);
    }

    // Accessor untuk format harga dengan mata uang
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk status aktif dalam bahasa Indonesia
    public function getStatusAktifTextAttribute()
    {
        return $this->status_aktif ? 'Aktif' : 'Tidak Aktif';
    }

    // Method untuk toggle status aktif
    public function toggleStatus()
    {
        $this->update(['status_aktif' => !$this->status_aktif]);
        return $this->status_aktif;
    }

    // Method untuk mendapatkan statistik penjualan
    public function getStatistikPenjualan()
    {
        return $this->detailAnggota()
            ->selectRaw('COUNT(*) as jumlah_terjual, SUM(harga_baju) as total_pendapatan')
            ->first();
    }

    // Method untuk mendapatkan ukuran berdasarkan kategori
    public static function getUkuranByKategori($kategori)
    {
        return self::where('kategori', $kategori)
            ->where('status_aktif', true)
            ->orderBy('harga', 'asc')
            ->get();
    }

    // Method untuk mendapatkan semua ukuran aktif
    public static function getAllAktif()
    {
        return self::where('status_aktif', true)
            ->orderBy('kategori', 'asc')
            ->orderBy('harga', 'asc')
            ->get();
    }
}
