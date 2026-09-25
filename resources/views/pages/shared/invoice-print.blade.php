@include('shared.invoice-print', ['invoice' => $invoice])
{{-- Info Rekening --}}
@php $rekening = \App\Models\RekeningBank::aktif()->get(); @endphp
@if ($rekening->isNotEmpty() && $invoice->status_bayar === 'belum_bayar')
    <div style="margin-top:24px;padding:16px;border:1px solid #e5e7eb;border-radius:8px;background:#f9fafb;">
        <p style="font-size:12px;font-weight:bold;color:#1B3A6B;margin-bottom:10px;">
            INFORMASI PEMBAYARAN
        </p>
        <p style="font-size:11px;color:#6b7280;margin-bottom:10px;">
            Transfer sesuai total invoice. Cantumkan kode <strong>{{ $invoice->kode_invoice }}</strong> sebagai berita acara
            transfer.
        </p>
        @foreach ($rekening as $rek)
            <div style="margin-bottom:8px;padding:8px;background:white;border:1px solid #e5e7eb;border-radius:6px;">
                <p style="font-size:11px;color:#6b7280;margin:0;">{{ $rek->nama_bank }}</p>
                <p style="font-size:14px;font-family:monospace;font-weight:bold;color:#333;margin:2px 0;">
                    {{ $rek->no_rekening }}</p>
                <p style="font-size:11px;color:#6b7280;margin:0;">a.n. {{ $rek->atas_nama }}</p>
            </div>
        @endforeach
    </div>
@endif