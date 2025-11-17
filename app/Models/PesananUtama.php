<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananUtama extends Model
{
    use HasFactory;

    protected $table = 'pesanan_utama';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'nama_pemesan',
        'nomor_whatsapp',
        'nomor_punggung',
        'nama_punggung',
        'total_pesanan',
        'total_harga',
        'status_pesanan',
        'style_request',
        'tanggal_pesan',
        'tanggal_update',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'tanggal_pesan' => 'datetime',
        'tanggal_update' => 'datetime',
    ];

    // Disable Laravel's default timestamps
    public $timestamps = false;

    // Custom timestamp columns
    const CREATED_AT = 'tanggal_pesan';
    const UPDATED_AT = 'tanggal_update';

    // Relasi dengan detail anggota
    public function detailAnggota(): HasMany
    {
        return $this->hasMany(DetailAnggota::class, 'id_pesanan', 'id_pesanan');
    }

    // Nomor resi/struk berbasis id_pesanan
    public function getNomorResiAttribute(): string
    {
        return 'jrsy' . $this->id_pesanan;
    }

    // Scope untuk status pesanan
    public function scopePending($query)
    {
        return $query->where('status_pesanan', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status_pesanan', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status_pesanan', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_pesanan', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status_pesanan', 'cancelled');
    }

    // Accessor untuk format nomor WhatsApp
    public function getFormattedWhatsappAttribute()
    {
        $whatsapp = $this->nomor_whatsapp;
        if (strpos($whatsapp, '0') === 0) {
            return '62' . substr($whatsapp, 1);
        }
        return $whatsapp;
    }

}
