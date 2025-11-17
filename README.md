# Admin Toko Jersey — Dokumentasi Proyek

Proyek ini adalah panel admin untuk mengelola pemesanan jersey: melihat statistik, membuat/mengelola pesanan, mencetak invoice (PDF), dan mengirim ringkasan ke WhatsApp. Aplikasi ditujukan untuk internal toko, bukan halaman pemesanan publik.

## Fitur Utama
- Dashboard: ringkasan (total pesanan, pendapatan, baju terjual, pending), distribusi status, penjualan per ukuran, grafik bulanan. Pendapatan mengecualikan pesanan `cancelled`.
- Manajemen Pesanan:
  - Form multi‑langkah: Data pelanggan, anggota keluarga, review.
  - Preview jersey (nama & nomor punggung) live, termasuk per anggota.
  - Field khusus: `style_request`, `nama_punggung`, `nama_di_jersey` per anggota.
  - Invoice (PDF) dan tombol kirim ringkasan ke WhatsApp.
- Daftar Pesanan: kolom resi `jrsy{id}`, pencarian lintas kolom, filter status (kompatibel dengan pencarian), ubah status via modal.
- Tata letak: sidebar bisa disembunyikan (state tersimpan), grid kartu dashboard adaptif (2×2 saat sidebar tampak, horizontal scroll saat disembunyikan).

## Mulai Cepat
### Prasyarat
- PHP 8.2+, Composer, server database (MySQL/MariaDB), ekstensi PHP standar Laravel.

### Instalasi
1. `composer install`
2. Salin env dan generate key:
   - `cp .env.example .env`
   - `php artisan key:generate`
3. Setel koneksi database di `.env` (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
4. Migrasi + seeding:
   - `php artisan migrate --seed`
   - Seeder membuat akun admin dan data ukuran serta 50 pesanan contoh.
5. Jalankan:
   - `php artisan serve`
6. Login admin:
   - Email: `admin@gmail.com`
   - Password: `jerseyhebat`

## Arsitektur Singkat
- Laravel (Blade) + Tailwind CDN + Flowbite + sedikit jQuery untuk UX ringan.
- Struktur utama:
  - Controller: `app/Http/Controllers/PesananController.php`, `AuthController.php`.
  - Model: `PesananUtama`, `DetailAnggota`, `MasterUkuran`, `User`.
  - View: `resources/views/layouts/admin.blade.php` (layout), halaman di `resources/views/pesanan`.
  - Script UI: `public/js/pesanan.js` (validasi, preview, alur form, modals).

## Konfigurasi
- Ubah variabel `.env` sesuai environment.
- Opsi seed besar: saat ini seeder pesanan di-set tetap 50. (Sesuaikan di `LargePesananSeeder` bila perlu.)

## Database & Relasi
- Tabel `pesanan_utama` (PK: `id_pesanan`): `nama_pemesan`, `nomor_whatsapp`, `nomor_punggung`, `nama_punggung`, `total_pesanan`, `total_harga`, `status_pesanan`, `style_request`, `tanggal_pesan`, `tanggal_update`.
  - Aksesori `nomor_resi` => `jrsy{id_pesanan}`.
- Tabel `detail_anggota`: detail per anggota (termasuk `nama_di_jersey`, `ukuran_baju`, `harga_baju`).
- Tabel `master_ukuran`: daftar ukuran & harga aktif.
- Tabel `users`: akun admin (login berbasis sesi).

## Alur & Aturan Bisnis
- Membuat Pesanan: isi data pelanggan → tentukan anggota → review → submit.
- Harga total dihitung dari harga ukuran per anggota (mengacu ke `master_ukuran`).
- Pendapatan dashboard mengecualikan status `cancelled`.
- Invoice PDF menyertakan `style_request`, `nama_punggung`, dan `nama_di_jersey` per anggota.

## Rute Penting
- Web (auth wajib): `/` (form), `/pesanan/list`, `/pesanan/{id}`, `/pesanan/{id}/invoice`, `/dashboard`.
- API (untuk UI): `/api/pemesan-list` (list + pencarian), validasi realtime (nama/WA/nomor punggung).
- Auth: `/login` (GET/POST), `/logout` (POST).

## Operasional
- Seeder:
  - `MasterUkuranSeeder`, `AdminUserSeeder`, `LargePesananSeeder` (50 pesanan dengan `style_request` unik bernuansa klub Serie A / timnas).
- PDF invoice menggunakan DomPDF (jika paket tersedia). Jika tidak, ditampilkan HTML fallback.

## Keamanan
- Proteksi middleware `auth` untuk halaman admin.
- CSRF aktif (meta + header di fetch).

## Troubleshooting
- Tanggal NULL: pastikan `tanggal_pesan`/`tanggal_update` termasuk di `$fillable` model `PesananUtama` (sudah diterapkan).
- Update status gagal: pastikan body JSON memakai `status_pesanan`.
- Nominal terpotong di dashboard: sudah ditangani (wrap & min-width).

## Kontribusi
- Ikuti gaya Laravel default. Pull request dipersilakan untuk perbaikan/peningkatan.

