<?php

namespace App\Http\Controllers;

use App\Models\PesananUtama;
use App\Models\DetailAnggota;
use App\Models\MasterUkuran;
use App\Rules\IndonesianPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    /**
     * Menampilkan halaman utama pemesanan
     */
    public function index()
    {
        $ukuranBaju = MasterUkuran::getAllAktif();
        return view('pesanan.index', compact('ukuranBaju'));
    }

    /**
     * Menyimpan pesanan baru
     */
    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'nama_pemesan' => ['required', 'string', 'max:100'],
            'nomor_whatsapp' => ['required', 'string', 'max:20', new IndonesianPhoneNumber()],
            'nomor_punggung' => ['required', 'integer', 'min:1', 'max:999'],
            'nama_punggung' => ['nullable', 'string', 'max:30'],
            'style_request' => ['nullable', 'string', 'max:2000'],
            'anggota' => 'required|array|min:1',
            'anggota.*.nama_anggota' => 'required|string|max:100',
            'anggota.*.nama_di_jersey' => 'nullable|string|max:30',
            'anggota.*.umur' => 'required|integer|min:1|max:100',
            'anggota.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota.*.ukuran_baju' => 'required|string|exists:master_ukuran,ukuran_baju',
        ], [
            'nama_pemesan.required' => 'Nama pemesan harus diisi',
            'nama_pemesan.max' => 'Nama pemesan maksimal 100 karakter',
            'nomor_whatsapp.required' => 'Nomor WhatsApp harus diisi',
            'nomor_whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter',
            'nomor_punggung.required' => 'Nomor punggung harus diisi',
            'nomor_punggung.integer' => 'Nomor punggung harus berupa angka',
            'nomor_punggung.min' => 'Nomor punggung minimal 1',
            'nomor_punggung.max' => 'Nomor punggung maksimal 999',
            'anggota.required' => 'Data anggota keluarga harus diisi',
            'anggota.min' => 'Minimal 1 anggota keluarga',
            'anggota.*.nama_anggota.required' => 'Nama anggota harus diisi',
            'anggota.*.nama_anggota.max' => 'Nama anggota maksimal 100 karakter',
            'anggota.*.umur.required' => 'Umur anggota harus diisi',
            'anggota.*.umur.integer' => 'Umur harus berupa angka',
            'anggota.*.umur.min' => 'Umur minimal 1 tahun',
            'anggota.*.umur.max' => 'Umur maksimal 100 tahun',
            'anggota.*.jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'anggota.*.jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'anggota.*.ukuran_baju.required' => 'Ukuran baju harus dipilih',
            'anggota.*.ukuran_baju.exists' => 'Ukuran baju tidak valid',
            'anggota.*.nama_di_jersey.max' => 'Nama di jersey maksimal 30 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Hitung total harga
            $totalHarga = 0;
            $totalPesanan = count($request->anggota);

            foreach ($request->anggota as $anggota) {
                $masterUkuran = MasterUkuran::where('ukuran_baju', $anggota['ukuran_baju'])->first();
                if ($masterUkuran) {
                    $totalHarga += $masterUkuran->harga;
                }
            }

            // Simpan pesanan utama
            $pesananUtama = PesananUtama::create([
                'nama_pemesan' => $request->nama_pemesan,
                'nomor_whatsapp' => $request->nomor_whatsapp,
                'nomor_punggung' => $request->nomor_punggung,
                'total_pesanan' => $totalPesanan,
                'total_harga' => $totalHarga,
                'status_pesanan' => 'pending',
                'nama_punggung' => $request->nama_punggung,
                'style_request' => $request->style_request,
                'tanggal_pesan' => now(),
                'tanggal_update' => now()
            ]);

            // Simpan detail anggota
            foreach ($request->anggota as $index => $anggota) {
                $masterUkuran = MasterUkuran::where('ukuran_baju', $anggota['ukuran_baju'])->first();
                
                DetailAnggota::create([
                    'id_pesanan' => $pesananUtama->id_pesanan,
                    'nama_anggota' => $anggota['nama_anggota'],
                    'nama_di_jersey' => $anggota['nama_di_jersey'] ?? null,
                    'umur' => $anggota['umur'],
                    'jenis_kelamin' => $anggota['jenis_kelamin'],
                    'ukuran_baju' => $anggota['ukuran_baju'],
                    'harga_baju' => $masterUkuran ? $masterUkuran->harga : 0,
                    'nomor_punggung' => $request->nomor_punggung,
                    'urutan_anggota' => $index + 1
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil disimpan!',
                'data' => [
                    'id_pesanan' => $pesananUtama->id_pesanan,
                    'total_harga' => $totalHarga,
                    'total_pesanan' => $totalPesanan
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan daftar pesanan (untuk admin)
     */
    public function list()
    {
        $q = request('q');
        $status = request('status');
        $pesanan = PesananUtama::with('detailAnggota')
            ->when($q, function($query) use ($q) {
                $like = "%{$q}%";
                $query->where(function($sub) use ($like) {
                    $sub->whereRaw("CONCAT('jrsy', id_pesanan) LIKE ?", [$like])
                        ->orWhere('id_pesanan', 'like', $like)
                        ->orWhere('nama_pemesan', 'like', $like)
                        ->orWhere('nomor_whatsapp', 'like', $like)
                        ->orWhere('nomor_punggung', 'like', $like)
                        ->orWhere('style_request', 'like', $like)
                        ->orWhere('status_pesanan', 'like', $like)
                        ->orWhere('tanggal_pesan', 'like', $like);
                });
            })
            ->when($status, function($query) use ($status) {
                $query->where('status_pesanan', $status);
            })
            ->orderBy('tanggal_pesan', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return view('pesanan.list', compact('pesanan'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function show($id)
    {
        $pesanan = PesananUtama::with('detailAnggota')->findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $pesanan
            ]);
        }
        
        // Return view for regular requests
        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pesanan' => 'required|in:pending,confirmed,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pesanan = PesananUtama::findOrFail($id);
            $pesanan->update([
                'status_pesanan' => $request->status_pesanan,
                'tanggal_update' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate',
                'data' => [
                    'status_pesanan' => $pesanan->status_pesanan
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan data ukuran baju
     */
    public function getUkuranBaju()
    {
        $ukuranBaju = MasterUkuran::getAllAktif();
        
        return response()->json([
            'success' => true,
            'data' => $ukuranBaju
        ]);
    }

    /**
     * API untuk mendapatkan harga berdasarkan ukuran
     */
    public function getHargaByUkuran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ukuran_baju' => 'required|string|exists:master_ukuran,ukuran_baju'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ukuran baju tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $masterUkuran = MasterUkuran::where('ukuran_baju', $request->ukuran_baju)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'ukuran_baju' => $masterUkuran->ukuran_baju,
                'harga' => $masterUkuran->harga,
                'harga_formatted' => $masterUkuran->harga_formatted,
                'kategori' => $masterUkuran->kategori
            ]
        ]);
    }

    /**
     * Validasi real-time untuk nama pemesan
     */
    public function validateCustomerName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemesan' => ['required', 'string', 'max:100']
        ]);

        return response()->json([
            'valid' => !$validator->fails(),
            'message' => $validator->fails() ? $validator->errors()->first('nama_pemesan') : 'Nama pemesan valid'
        ]);
    }

    /**
     * Validasi real-time untuk nomor WhatsApp
     */
    public function validateWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_whatsapp' => ['required', 'string', 'max:20', new IndonesianPhoneNumber()]
        ]);

        return response()->json([
            'valid' => !$validator->fails(),
            'message' => $validator->fails() ? $validator->errors()->first('nomor_whatsapp') : 'Nomor WhatsApp tersedia'
        ]);
    }

    /**
     * Validasi real-time untuk nomor punggung
     */
    public function validateJerseyNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_punggung' => ['required', 'integer', 'min:1', 'max:999']
        ]);

        return response()->json([
            'valid' => !$validator->fails(),
            'message' => $validator->fails() ? $validator->errors()->first('nomor_punggung') : 'Nomor punggung tersedia'
        ]);
    }

    /**
     * API untuk mendapatkan list pemesan (untuk ditampilkan di halaman utama)
     */
    public function getPemesanList(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $query = PesananUtama::with('detailAnggota')
            ->orderBy('tanggal_pesan', 'desc');

        if ($search) {
            $like = "%{$search}%";
            $query->where(function($q) use ($like) {
                $q->where('nama_pemesan', 'like', $like)
                  ->orWhere('nomor_whatsapp', 'like', $like)
                  ->orWhere('nomor_punggung', 'like', $like)
                  ->orWhere('style_request', 'like', $like)
                  ->orWhere('status_pesanan', 'like', $like)
                  ->orWhere('tanggal_pesan', 'like', $like)
                  ->orWhereRaw("CONCAT('jrsy', id_pesanan) LIKE ?", [$like])
                  ->orWhere('id_pesanan', 'like', $like);
            });
        }

        $pemesan = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $pemesan
        ]);
    }

    /**
     * Dashboard statistik
     */
    public function dashboard()
    {
        $stats = [
            'total_pesanan' => PesananUtama::count(),
            'pesanan_pending' => PesananUtama::pending()->count(),
            'pesanan_confirmed' => PesananUtama::confirmed()->count(),
            'pesanan_processing' => PesananUtama::processing()->count(),
            'pesanan_completed' => PesananUtama::completed()->count(),
            'total_pendapatan' => PesananUtama::where('status_pesanan', '!=', 'cancelled')->sum('total_harga'),
            'total_baju_terjual' => PesananUtama::where('status_pesanan', '!=', 'cancelled')->sum('total_pesanan')
        ];

        // Statistik per bulan
        $statistikBulanan = PesananUtama::selectRaw('
                DATE_FORMAT(tanggal_pesan, "%Y-%m") as bulan,
                COUNT(*) as jumlah_pesanan,
                SUM(total_pesanan) as total_baju,
                SUM(total_harga) as total_pendapatan
            ')
            ->where('status_pesanan', '!=', 'cancelled')
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        // Statistik per ukuran
        $statistikUkuran = DetailAnggota::selectRaw('
                ukuran_baju,
                COUNT(*) as jumlah_terjual,
                SUM(harga_baju) as total_pendapatan
            ')
            ->join('pesanan_utama', 'detail_anggota.id_pesanan', '=', 'pesanan_utama.id_pesanan')
            ->where('pesanan_utama.status_pesanan', '!=', 'cancelled')
            ->groupBy('ukuran_baju')
            ->orderBy('jumlah_terjual', 'desc')
            ->get();

        return view('pesanan.dashboard', compact('stats', 'statistikBulanan', 'statistikUkuran'));
    }

    /**
     * Generate/download Invoice PDF for a specific order
     */
    public function invoice($id)
    {
        $pesanan = PesananUtama::with('detailAnggota')->findOrFail($id);

        // If dompdf facade available, stream/download PDF; otherwise, show HTML view
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pesanan.invoice', compact('pesanan'));
            $filename = 'invoice-pesanan-'. $pesanan->id_pesanan .'.pdf';
            return $pdf->download($filename);
        }

        // Fallback HTML if package not installed yet
        return view('pesanan.invoice', compact('pesanan'));
    }
}
