@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.invoices.index') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Invoice</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $invoice->kode_invoice }}</p>
                </div>
            </div>
            <a href="{{ route('customer.invoices.print', $invoice) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Kolom Kiri --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Rincian Biaya --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Rincian Biaya</h3>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Harga Unit</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                Rp {{ number_format($invoice->harga, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Biaya Pengiriman</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                Rp {{ number_format($invoice->biaya_pengiriman, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                            <dt class="text-base font-bold text-gray-800 dark:text-white/90">Total</dt>
                            <dd class="text-base font-bold text-gray-800 dark:text-white/90">
                                {{ $invoice->total_format }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Info Unit --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit yang Dipesan</h3>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Motor</dt>
                            <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $invoice->order->unit->tipe_motor }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">No. Rangka</dt>
                            <dd class="text-sm font-mono text-gray-800 dark:text-white/90">
                                {{ $invoice->order->unit->no_rangka }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Warna</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->unit->warna }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Tahun</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->unit->tahun ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Info Pengiriman --}}
                @if ($invoice->order->pengiriman)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Info Pengiriman</h3>
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Kode Pengiriman</dt>
                                <dd class="text-sm font-mono text-gray-800 dark:text-white/90">
                                    {{ $invoice->order->pengiriman->kode_pengiriman }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Tujuan</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $invoice->order->pengiriman->tujuan }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Driver</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $invoice->order->pengiriman->driver->user->name }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                @endif

            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-6">

                {{-- Status Pembayaran --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status Pembayaran</h3>
                    @if ($invoice->status_bayar === 'sudah_bayar')
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-success-50 dark:bg-success-500/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-success-600 dark:text-success-400">Lunas</p>
                                @if ($invoice->paid_at)
                                    <p class="text-xs text-gray-400">{{ $invoice->paid_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-warning-600 dark:text-warning-400">Menunggu Pembayaran</p>
                                <p class="text-xs text-gray-400">Hubungi admin untuk konfirmasi</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Info Invoice --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Info Invoice</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Kode Invoice</dt>
                            <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $invoice->kode_invoice }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Kode Order</dt>
                            <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $invoice->order->kode_order }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Tanggal Invoice</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->created_at->format('d M Y') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Cetak --}}
                <a href="{{ route('customer.invoices.print', $invoice) }}" target="_blank"
                    class="flex items-center justify-center gap-2 w-full rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Invoice
                </a>

            </div>
        </div>
        {{-- Info Rekening Bank --}}
        @if ($invoice->status_bayar === 'belum_bayar')
            @php $rekening = \App\Models\RekeningBank::aktif()->get(); @endphp
            @if ($rekening->isNotEmpty())
                <div class="rounded-2xl border border-brand-200 bg-brand-50 p-6 dark:border-brand-500/30 dark:bg-brand-500/10">
                    <h3 class="text-sm font-semibold text-brand-700 dark:text-brand-400 mb-3">
                        Informasi Pembayaran
                    </h3>
                    <p class="text-xs text-brand-600 dark:text-brand-500 mb-3">
                        Transfer sesuai total invoice. Cantumkan kode invoice
                        <strong>{{ $invoice->kode_invoice }}</strong> sebagai berita acara.
                    </p>
                    <div class="space-y-3">
                        @foreach ($rekening as $rek)
                            <div class="rounded-lg bg-white dark:bg-gray-900 p-3 border border-brand-100 dark:border-brand-500/20">
                                <p class="text-xs text-gray-400 mb-0.5">{{ $rek->nama_bank }}</p>
                                <p class="text-sm font-mono font-bold text-gray-800 dark:text-white/90">
                                    {{ $rek->no_rekening }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">a.n. {{ $rek->atas_nama }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Upload Bukti Transfer --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                    {{ $invoice->status_verifikasi === 'ditolak' ? 'Upload Ulang Bukti Transfer' : 'Upload Bukti Transfer' }}
                </h3>

                {{-- Notif ditolak --}}
                @if ($invoice->status_verifikasi === 'ditolak')
                    <div
                        class="mb-4 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
                        <p class="text-xs font-medium text-error-600 dark:text-error-400">Bukti ditolak admin</p>
                        @if ($invoice->catatan_tolak)
                            <p class="text-xs text-error-500 mt-0.5">{{ $invoice->catatan_tolak }}</p>
                        @endif
                    </div>
                @endif

                {{-- Preview bukti yang sudah diupload --}}
                @if ($invoice->bukti_bayar && $invoice->status_verifikasi === 'menunggu_verifikasi')
                    <div
                        class="mb-4 rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 dark:border-warning-500/30 dark:bg-warning-500/10">
                        <p class="text-xs font-medium text-warning-600 dark:text-warning-400">
                            Bukti sedang diverifikasi admin
                        </p>
                        <p class="text-xs text-warning-500 mt-0.5">
                            Diupload {{ $invoice->tgl_upload_bukti->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <img src="{{ asset('storage/' . $invoice->bukti_bayar) }}" alt="Bukti Transfer"
                        class="w-full rounded-lg border border-gray-200 dark:border-gray-700 mb-4" />
                @endif

                @if ($invoice->status_verifikasi !== 'menunggu_verifikasi')
                    <form method="POST" action="{{ route('customer.invoices.upload-bukti', $invoice) }}"
                        enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <input type="file" name="bukti_bayar" accept="image/jpg,image/jpeg,image/png" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 file:mr-4 file:rounded file:border-0 file:bg-brand-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-brand-600 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                                    {{ $errors->has('bukti_bayar') ? 'border-error-400' : '' }}" />
                            @error('bukti_bayar')
                                <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Format JPG, JPEG, PNG. Maks 2MB.</p>
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                            Upload Bukti Transfer
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection