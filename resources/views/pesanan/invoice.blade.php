<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pesanan {{ $pesanan->nomor_resi }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        .container { width: 100%; margin: 0 auto; }
        .header { display:flex; justify-content: space-between; align-items:center; margin-bottom: 16px; }
        .title { font-size: 20px; font-weight: bold; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 4px; background:#0A2146; color:#fff; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; text-align: left; }
        .right { text-align: right; }
        .mt-2{ margin-top:8px; } .mt-4{ margin-top:16px; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Invoice Pesanan</div>
            <div class="badge">{{ $pesanan->nomor_resi }}</div>
        </div>
        <table>
            <tr>
                <th>Nama Pemesan</th>
                <td>{{ $pesanan->nama_pemesan }}</td>
            </tr>
            <tr>
                <th>Nomor WhatsApp</th>
                <td>{{ $pesanan->nomor_whatsapp }}</td>
            </tr>
            <tr>
                <th>Nomor Punggung</th>
                <td>#{{ $pesanan->nomor_punggung }}</td>
            </tr>
            @if(!empty($pesanan->nama_punggung))
            <tr>
                <th>Nama di Punggung</th>
                <td>{{ strtoupper($pesanan->nama_punggung) }}</td>
            </tr>
            @endif
            @if(!empty($pesanan->style_request))
            <tr>
                <th>Permintaan Desain/Style</th>
                <td>{{ $pesanan->style_request }}</td>
            </tr>
            @endif
            <tr>
                <th>Tanggal Pesan</th>
                <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <div class="mt-4">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Nama Anggota</th>
                        <th>Nama di Jersey</th>
                        <th style="width:60px">Umur</th>
                        <th style="width:100px">Jenis Kelamin</th>
                        <th style="width:120px">Ukuran</th>
                        <th class="right" style="width:120px">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan->detailAnggota as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $d->nama_anggota }}</td>
                        <td>{{ $d->nama_di_jersey ?? '-' }}</td>
                        <td>{{ $d->umur }}</td>
                        <td>{{ $d->jenis_kelamin }}</td>
                        <td>{{ $d->ukuran_baju }}</td>
                        <td class="right">Rp{{ number_format($d->harga_baju,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="right">Jumlah Baju</th>
                        <th class="right">{{ $pesanan->total_pesanan }} pcs</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="right">Total Harga</th>
                        <th class="right">Rp{{ number_format($pesanan->total_harga,0,',','.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            <small>Terima kasih telah memesan. Simpan invoice ini sebagai bukti pemesanan.</small>
        </div>
    </div>
</body>
</html>
