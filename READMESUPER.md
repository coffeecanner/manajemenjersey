# Admin Toko Jersey – Dokumentasi Super Lengkap

Dokumen ini adalah versi “super README” untuk proyek **Admin Toko Jersey**. Harapannya, siapa pun—engineer baru, QA, ops, bahkan stakeholder non-teknis—dapat memahami tujuan sistem, bagaimana cara menjalankannya, bagaimana struktur datanya, hingga praktik operasionalnya hanya dengan membaca berkas ini.

> **Catatan**: README asli (`README.md`) tetap tersedia sebagai ringkasan cepat. Berkas ini menyajikan versi yang jauh lebih detail.

---

## Daftar Isi
1. [Gambaran Proyek](#gambaran-proyek)
2. [Teknologi dan Dependensi](#teknologi-dan-dependensi)
3. [Struktur Direktori Penting](#struktur-direktori-penting)
4. [Arsitektur & Alur Tingkat Tinggi](#arsitektur--alur-tingkat-tinggi)
5. [Fitur Utama](#fitur-utama)
6. [Konfigurasi Lingkungan](#konfigurasi-lingkungan)
7. [Langkah Instalasi & Menjalankan Aplikasi](#langkah-instalasi--menjalankan-aplikasi)
8. [Skema Basis Data](#skema-basis-data)
9. [Perhitungan Bisnis & Layanan Utilitas](#perhitungan-bisnis--layanan-utilitas)
10. [Routing & API](#routing--api)
11. [Perilaku Front-End & UX](#perilaku-front-end--ux)
12. [Seeder & Data Dummy](#seeder--data-dummy)
13. [Testing & Kualitas](#testing--kualitas)
14. [Deployment & Operasional](#deployment--operasional)
15. [Monitoring, Logging, dan Troubleshooting](#monitoring-logging-dan-troubleshooting)
16. [Keamanan & Kepatuhan](#keamanan--kepatuhan)
17. [Roadmap & Ide Pengembangan](#roadmap--ide-pengembangan)
18. [Lampiran](#lampiran)

---

## Gambaran Proyek
Admin Toko Jersey merupakan panel internal untuk tim penjualan jersey keluarga. Sistem ini memusatkan pendaftaran pesanan, memonitor statistik penjualan, memproduksi invoice, serta menyediakan ringkasan yang siap dikirim via WhatsApp. Halaman ini tidak ditujukan untuk pelanggan akhir; semua workflow berada di tangan admin internal.

Nilai bisnis utama:
- Menghilangkan pencatatan manual pesanan.
- Mengurangi kesalahan input ukuran/harga dengan validasi real time.
- Memberikan visibilitas menyeluruh terhadap pendapatan, jumlah pesanan, dan status produksi.
- Mempermudah pembuatan invoice digital (PDF) dan komunikasi ke pelanggan.

---

## Teknologi dan Dependensi
- **Bahasa/Runtime**: PHP 8.2.
- **Framework**: Laravel 12.
- **Database**: MySQL / MariaDB (lihat `database` migrasi dan seed).
- **UI**: Blade template + Tailwind (CDN) + ikon Font Awesome + sedikit interaksi custom JS (`public/js/pesanan.js`).
- **PDF**: `barryvdh/laravel-dompdf` untuk invoice.
- **Testing**: PHPUnit 11, Laravel TestCase bawaan.
- **Utility/Dev Tools**:
  - `laravel/pint` untuk formatting.
  - `laravel/sail` & `laravel/pail` (opsional dev environment).
  - `mockery/mockery` (mocking pada test kompleks jika dibutuhkan).

Dependensi lengkap tersedia di `composer.json`.

---

## Struktur Direktori Penting
| Lokasi | Deskripsi |
| --- | --- |
| `app/Http/Controllers/PesananController.php` | Inti logika bisnis (CRUD pesanan, dashboard, API). |
| `app/Models/` | Definisi model Eloquent: `PesananUtama`, `DetailAnggota`, `MasterUkuran`. |
| `app/Services/PenghitunganHargaService.php` | Utilitas khusus untuk menjumlahkan harga anggota. |
| `resources/views/` | Blade template (layout, halaman pesanan, dashboard). |
| `public/js/pesanan.js` | Script multi-step form, validasi, preview jersey, pemanggilan API. |
| `database/seeders/` | Seeder akun admin, master ukuran, dan dataset pesanan contoh. |
| `tests/` | Test automatis (unit & feature). |
| `routes/web.php` | Definisi rute web & API internal. |

---

## Arsitektur & Alur Tingkat Tinggi
1. **Authentication Layer**: Halaman admin dilindungi `auth` middleware. Hanya user terdaftar (dibuat oleh seeder) yang dapat mengakses form.
2. **Form Multi Step**:
   - Step 1: Data pemesan (nama, WhatsApp, nomor punggung, jumlah anggota, style request, dll).
   - Step 2: Data setiap anggota (nama, umur, gender, ukuran baju, nama jersey, preview).
   - Step 3: Review seluruh data + ringkasan harga sebelum submit.
3. **Submission**:
   - `PesananController::store` menjalankan validasi, menghitung total harga per anggota (berdasarkan `master_ukuran`), menyimpan `pesanan_utama` dan `detail_anggota`.
   - Data disimpan dalam transaksi DB untuk mencegah partial insert.
4. **Visualisasi**:
   - Dashboard menampilkan statistik real-time: total pesanan, total pendapatan (kecuali `cancelled`), distribusi status, penjualan per ukuran.
   - Tabel pesanan dengan pencarian global dan filter status.
5. **Dokumentasi & Komunikasi**:
   - Invoice PDF (Dompdf) dapat diunduh/dikirim.
   - Tombol ringkasan WhatsApp memformat data sehingga siap ditempel di aplikasi WA.

---

## Fitur Utama
- **Manajemen Pesanan**: Form interaktif + preview jersey per anggota.
- **Validasi Real-Time**: Ajax ke API untuk memeriksa duplikasi nama, nomor WhatsApp, dan nomor punggung.
- **Perhitungan Otomatis**: Harga anggota mengacu ke `master_ukuran`; total dihitung otomatis (utilitas `PenghitunganHargaService`).
- **Daftar Pesanan Lengkap**: Kolom ID, pemesan, WhatsApp, total, status. Aksi edit status via modal.
- **Dashboard Analitik**: Ringkasan KPI, chart bulanan, breakdown ukuran favorit.
- **Invoice PDF**: Termasuk style request, detail anggota, total biaya, barcode/resi.
- **WhatsApp Integration (manual)**: Ringkasan siap kirim (copy & paste).

---

## Konfigurasi Lingkungan
1. **Prasyarat**:
   - PHP 8.2+, Composer, ekstensi standar (mbstring, openssl, pdo).
   - Database MySQL/MariaDB.
   - Node.js hanya diperlukan jika ingin menjalankan Vite (opsional; UI saat ini mengandalkan CDN).
2. **Berkas `.env`**:
   - `APP_URL`, `APP_ENV`, `APP_DEBUG`.
   - Database (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   - `MAIL_*` jika ingin mengirim email (tidak wajib).
   - Konfigurasi tambahan (misal `DOMPDF_*`) jika dibutuhkan untuk PDF.

---

## Langkah Instalasi & Menjalankan Aplikasi
1. **Clone** repo dan pindah ke direktori.
2. Jalankan `composer install`.
3. **Env & Key**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Migrasi & Seed**:
   ```bash
   php artisan migrate --seed
   ```
   Ini akan menjalankan:
   - Seeder admin (`admin@gmail.com` / `jerseyhebat`).
   - Seeder master ukuran.
   - Seeder pesanan besar (50 contoh realistik).
5. **Serve**:
   ```bash
   php artisan serve
   ```
   atau gunakan Sail/Pail sesuai preferensi.
6. **Login** di `/login` menggunakan kredensial seeder untuk mengakses admin panel.

---

## Skema Basis Data
### Tabel `master_ukuran`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id_ukuran` | PK | Auto increment |
| `ukuran_baju` | string | Nama ukuran (mis: Dewasa M) |
| `kategori` | enum | `Anak` / `Dewasa` |
| `harga` | decimal(8,2) | Harga per baju |
| `status_aktif` | boolean | Hanya ukuran aktif muncul di form |
| `tanggal_buat` | datetime | Timestamp custom |

### Tabel `pesanan_utama`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id_pesanan` | PK | ID utama |
| `nama_pemesan` | string(100) | Nama pelanggan |
| `nomor_whatsapp` | string(20) | Format nasional (08/62) |
| `nomor_punggung` | integer | Dipakai semua anggota |
| `nama_punggung` | string nullable | Nama di jersey |
| `total_pesanan` | int | Jumlah anggota |
| `total_harga` | decimal(10,2) | Akumulasi harga anggota |
| `status_pesanan` | enum | `pending`, `confirmed`, `processing`, `completed`, `cancelled` |
| `style_request` | text | Catatan desain |
| `tanggal_pesan` / `tanggal_update` | datetime | Timestamp custom |

### Tabel `detail_anggota`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id_detail` | PK | Auto increment |
| `id_pesanan` | FK | Relasi ke `pesanan_utama` |
| `nama_anggota` | string(100) | Nama anggota |
| `nama_di_jersey` | string(30) nullable | Custom nama jersey |
| `umur` | int | Valid 1-100 |
| `jenis_kelamin` | enum | `Laki-laki` / `Perempuan` |
| `ukuran_baju` | string | Relasi ke `master_ukuran` berdasarkan nama |
| `harga_baju` | decimal | Snapshot harga saat pemesanan |
| `nomor_punggung` | int | Mengikuti pemesan |
| `urutan_anggota` | int | Posisi anggota pada pesanan |

Relasi: `PesananUtama` memiliki banyak `DetailAnggota`. `DetailAnggota` dapat mengakses `MasterUkuran` untuk referensi harga/kategori.

---

## Perhitungan Bisnis & Layanan Utilitas
- **Total Harga**:
  - Ditentukan dengan menjumlahkan harga per anggota menggunakan harga terbaru dari `master_ukuran`.
  - Tersedia helper `App\Services\PenghitunganHargaService::hitungTotalHarga(array $hargaPerAnggota)` untuk memudahkan testing/isolasi logika.
- **Nomor Resi**: `PesananUtama` memiliki accessor `nomor_resi` yang secara otomatis menghasilkan `jrsy{id}`.
- **Status Tracking**: Model menyediakan scope `pending`, `confirmed`, dll. Memudahkan query dashboard.
- **Format WhatsApp**: Accessor `formatted_whatsapp` memastikan nomor diawali `62`.
- **Preview Frontend**: `public/js/pesanan.js` memperbarui preview jersey, validasi, summary, dan ringkasan WA.

---

## Routing & API
Semua rute berada di `routes/web.php`.

### Autentikasi
| Method | Path | Handler | Keterangan |
| --- | --- | --- | --- |
| GET | `/login` | `AuthController@showLogin` | Form login |
| POST | `/login` | `AuthController@login` | Proses login |
| POST | `/logout` | `AuthController@logout` | Logout |

### Halaman Utama (protected)
| Method | Path | Handler | Keterangan |
| --- | --- | --- | --- |
| GET | `/` | `PesananController@index` | Form pemesanan |
| GET | `/pesanan/list` | `PesananController@list` | Daftar pesanan |
| GET | `/pesanan/{id}` | `PesananController@show` | Detail pesanan |
| GET | `/pesanan/{id}/invoice` | `PesananController@invoice` | PDF invoice |
| POST | `/pesanan` | `PesananController@store` | Simpan pesanan baru |
| PATCH | `/pesanan/{id}/status` | `PesananController@updateStatus` | Ubah status |
| GET | `/dashboard` | `PesananController@dashboard` | Dashboard statistik |

### API Internal (AJAX)
Semua berada di prefix `/api` (masih di dalam middleware `auth`).
| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/api/ukuran-baju` | Data ukuran aktif. |
| GET | `/api/harga-ukuran` | Harga berdasarkan nama ukuran. |
| POST | `/api/validate-customer-name` | Validasi nama pemesan unik. |
| POST | `/api/validate-whatsapp` | Validasi nomor WA unik. |
| POST | `/api/validate-jersey-number` | Validasi nomor punggung tidak bentrok. |
| GET | `/api/pemesan-list` | Data tabel pesanan (search/paging). |

Semua endpoint mengembalikan JSON dengan struktur standar: `success`, `message`, `data`/`errors`.

---

## Perilaku Front-End & UX
- File inti: `public/js/pesanan.js`.
- **State Management Lokal**: `orderData` menyimpan pemesan, anggota, total.
- **Validasi Step**:
  - Step 1: memverifikasi nama, WA (regex Indonesia), nomor punggung (1-999), jumlah anggota.
  - Step 2: validasi input per anggota + preview jersey.
- **Dynamic Form**: `generateFamilyForms()` membangun card per anggota menggunakan data `window.UKURAN_BAJU` yang dipasang dari Blade.
- **Price Sync**: `updatePrice()` membaca `data-harga` dari `<option>` dan memperbarui display + summary total.
- **Summary**: `generateSummary()` menampilkan rekap di langkah 3 termasuk table per anggota dan total harga.
- **UX Enhancement**:
  - Progress bar + persentase.
  - Smooth scroll antar step.
  - Keyboard navigation (Enter = Next Step).
  - Preview jersey global (Step 1) serta per anggota.
- **WhatsApp Message Builder**: Bagian akhir menyiapkan string multi-baris (`lastSubmission`) yang siap disalin ke WA.

---

## Seeder & Data Dummy
- `MasterUkuranSeeder`: daftar ukuran standar (S/M/L anak, S-XXXL dewasa).
- `AdminUserSeeder`: akun admin default (`admin@gmail.com` / `jerseyhebat`).
- `LargePesananSeeder`: membuat 50 pesanan contoh:
  - Nama pemesan acak (`Faker` Indonesia).
  - Variasi status (pending, confirmed, processing, completed, cancelled).
  - `style_request` unik terinspirasi klub Serie A dan timnas (home/away/third).
  - Jumlah anggota 2-6 dengan data anggota detail.
  - Transaksi dibungkus DB::transaction untuk menjaga konsistensi.

Seeder otomatis dijalankan via `php artisan migrate --seed`. Anda bisa menjalankan seeder tertentu menggunakan `php artisan db:seed --class=NamaSeeder`.

---

## Testing & Kualitas
- **Unit Test**:
  - `tests/Unit/HitungTotalHargaTest.php` menguji `PenghitunganHargaService` pada beberapa skenario (array kosong, nilai besar, desimal).
- **Menjalankan Test**:
  ```bash
  php vendor/phpunit/phpunit/phpunit --testsuite=Unit
  # atau keseluruhan Laravel
  php artisan test
  ```
- **Best Practice**:
  - Tambahkan test baru untuk logika kritis (perhitungan, validasi API) sebelum refactor.
  - Gunakan `Tests\TestCase` untuk memanfaatkan helper Laravel (factory, actingAs, dll).

---

## Deployment & Operasional
1. **Build Artifact**: karena UI mengandalkan CDN, tidak ada proses build khusus. Pastikan `composer install --optimize-autoloader --no-dev` untuk production.
2. **Migrasi**: jalankan `php artisan migrate --force` saat deploy.
3. **Cache Optimization**:
   - `php artisan config:cache`
   - `php artisan route:cache`
4. **Storage**: pastikan `storage/` dan `bootstrap/cache/` writable untuk log & cache.
5. **Cron/Queue**: tidak ada job terjadwal saat ini. Jika menambahkannya (misal notifikasi status), gunakan `php artisan schedule:work` atau queue worker standar.
6. **PDF Requirements**: Dompdf memerlukan ekstensi `gd` & `mbstring`. Pastikan terpasang di server produksi.

---

## Monitoring, Logging, dan Troubleshooting
- **Log Aplikasi**: `storage/logs/laravel.log`. Gunakan tail untuk real-time (`tail -f`).
- **Error Umum**:
  - *Validasi gagal*: respons JSON 422; cek payload input.
  - *Dompdf error*: biasanya karena font/encoding. Pastikan folder `storage/fonts` writable.
  - *Nilai statistik tidak update*: periksa cron (jika menambahkan job) atau pastikan status tidak `cancelled` untuk masuk perhitungan.
  - *Tombol “Lanjutkan” tidak aktif*: pastikan field wajib (termasuk `Jumlah Anggota`) terisi; JS memvalidasi ketat.
- **Debugging**:
  - Gunakan `php artisan tinker` untuk memeriksa data.
  - Aktifkan `APP_DEBUG=true` hanya di environment dev.

---

## Keamanan & Kepatuhan
- Semua halaman admin berada di dalam middleware `auth`.
- Form dan AJAX telah dilindungi token CSRF (Blade `@csrf` + meta tag + header JS).
- Sanitasi input dilakukan via Laravel Validator di `PesananController::store`.
- **Saran**:
  - Gunakan HTTPS di production (`APP_URL`).
  - Batasi akses admin dengan VPN/IP allowlist jika memungkinkan.
  - Putar password default admin setelah instalasi production.

---

## Roadmap & Ide Pengembangan
1. **Automasi Notifikasi**: Integrasi Twilio/WhatsApp API untuk mengirim ringkasan secara otomatis.
2. **Role Management**: Tambahkan role operator vs supervisor dengan hak akses berbeda.
3. **Ekspor Data**: CSV/Excel untuk laporan keuangan.
4. **Integrasi Payment**: Menandai status `paid` otomatis berdasarkan gateway.
5. **Analytics Lanjutan**: Breakdown per hari, heatmap penjualan, leaderboard ukuran.
6. **Testing Tambahan**: Feature test untuk API validasi dan submission multi-step.

---

## Lampiran
- **Kredensial Default**:
  - Email: `admin@gmail.com`
  - Password: `jerseyhebat`
- **Command Berguna**:
  - `php artisan migrate:fresh --seed` – Reset database.
  - `php artisan make:test NamaTest` – Membuat test baru.
  - `php artisan make:seeder NamaSeeder` – Membuat seeder tambahan.
- **Resource Referensi**:
  - Laravel Docs 12.x – https://laravel.com/docs
  - Tailwind Docs – https://tailwindcss.com/docs
  - Dompdf – https://github.com/barryvdh/laravel-dompdf

---

Selamat menggunakan Admin Toko Jersey! Jika menemukan bug atau ingin menambahkan fitur, silakan buat issue atau pull request sambil merujuk ke berkas ini agar diskusi tetap kontekstual.
