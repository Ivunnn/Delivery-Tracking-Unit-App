<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->kode_invoice }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 2px solid #1B3A6B; }
        .company-name { font-size: 20px; font-weight: bold; color: #1B3A6B; }
        .company-sub { font-size: 12px; color: #666; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 24px; font-weight: bold; color: #1B3A6B; }
        .invoice-title p { font-size: 12px; color: #666; margin-top: 4px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; margin-top: 6px; }
        .status-lunas { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
        .info-box h3 { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #666; margin-bottom: 8px; letter-spacing: 0.5px; }
        .info-box p { font-size: 13px; color: #333; line-height: 1.6; }
        .info-box .mono { font-family: monospace; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #1B3A6B; color: white; }
        thead th { padding: 10px 14px; text-align: left; font-size: 12px; }
        tbody tr { border-bottom: 1px solid #eee; }
        tbody td { padding: 10px 14px; font-size: 13px; }
        .text-right { text-align: right; }
        .total-section { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #eee; }
        .total-row.final { font-weight: bold; font-size: 15px; border-bottom: none; padding-top: 10px; color: #1B3A6B; }
        .footer { margin-top: 48px; display: flex; justify-content: space-between; align-items: flex-end; }
        .sign-box { text-align: center; }
        .sign-box .sign-line { width: 160px; border-top: 1px solid #333; margin-top: 60px; padding-top: 6px; font-size: 12px; }
        .note { font-size: 11px; color: #888; margin-top: 32px; padding-top: 16px; border-top: 1px solid #eee; text-align: center; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    {{-- Tombol Cetak (hilang saat print) --}}
    <div class="no-print" style="margin-bottom: 24px;">
        <button onclick="window.print()"
            style="background:#1B3A6B;color:white;border:none;padding:10px 24px;border-radius:8px;font-size:13px;cursor:pointer;margin-right:8px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:10px 24px;border-radius:8px;font-size:13px;cursor:pointer;">
            Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">CV. Anugerah Bojonegoro</div>
            <div class="company-sub">Distributor Sepeda Motor</div>
            <div class="company-sub">Bojonegoro, Jawa Timur</div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p class="mono">{{ $invoice->kode_invoice }}</p>
            <p>{{ $invoice->created_at->format('d M Y') }}</p>
            <span class="status-badge {{ $invoice->status_bayar === 'sudah_bayar' ? 'status-lunas' : 'status-pending' }}">
                {{ $invoice->status_bayar === 'sudah_bayar' ? '✓ LUNAS' : '⏳ BELUM BAYAR' }}
            </span>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-box">
            <h3>Ditagihkan Kepada</h3>
            <p><strong>{{ $invoice->order->customer->name }}</strong></p>
            @if ($invoice->order->customer->nama_toko)
                <p>{{ $invoice->order->customer->nama_toko }}</p>
            @endif
            <p>{{ $invoice->order->customer->email }}</p>
            <p>{{ $invoice->order->customer->phone ?? '-' }}</p>
            @if ($invoice->order->customer->alamat)
                <p>{{ $invoice->order->customer->alamat }}</p>
            @endif
            @if ($invoice->order->customer->kota)
                <p>{{ $invoice->order->customer->kota }}</p>
            @endif
        </div>
        <div class="info-box">
            <h3>Info Invoice</h3>
            <p>No. Invoice: <span class="mono">{{ $invoice->kode_invoice }}</span></p>
            <p>No. Order: <span class="mono">{{ $invoice->order->kode_order }}</span></p>
            <p>Tanggal: {{ $invoice->created_at->format('d M Y') }}</p>
            @if ($invoice->status_bayar === 'sudah_bayar' && $invoice->paid_at)
                <p>Tgl Bayar: {{ $invoice->paid_at->format('d M Y') }}</p>
            @endif
        </div>
    </div>

    {{-- Tabel Unit --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>No. Rangka</th>
                <th class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    {{ $invoice->order->unit->tipe_motor }}
                    · {{ $invoice->order->unit->warna }}
                    @if ($invoice->order->unit->tahun)
                        · {{ $invoice->order->unit->tahun }}
                    @endif
                </td>
                <td class="mono">{{ $invoice->order->unit->no_rangka }}</td>
                <td class="text-right">Rp {{ number_format($invoice->harga, 0, ',', '.') }}</td>
            </tr>
            @if ($invoice->biaya_pengiriman > 0)
                <tr>
                    <td>2</td>
                    <td>Biaya Pengiriman</td>
                    <td>-</td>
                    <td class="text-right">Rp {{ number_format($invoice->biaya_pengiriman, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($invoice->harga, 0, ',', '.') }}</span>
        </div>
        @if ($invoice->biaya_pengiriman > 0)
            <div class="total-row">
                <span>Biaya Pengiriman</span>
                <span>Rp {{ number_format($invoice->biaya_pengiriman, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="total-row final">
            <span>TOTAL</span>
            <span>{{ $invoice->total_format }}</span>
        </div>
    </div>

    {{-- Tanda Tangan --}}
    <div class="footer">
        <div class="sign-box">
            <div class="sign-line">Customer</div>
            <p style="font-size:12px;margin-top:4px;">{{ $invoice->order->customer->name }}</p>
        </div>
        <div class="sign-box">
            <div class="sign-line">CV. Anugerah Bojonegoro</div>
            <p style="font-size:12px;margin-top:4px;">Administrator</p>
        </div>
    </div>

    <div class="note">
        Dokumen ini dicetak secara otomatis oleh Sistem Delivery Tracking Unit — CV. Anugerah Bojonegoro
        · {{ now()->format('d M Y, H:i') }} WIB
    </div>

</body>
</html>