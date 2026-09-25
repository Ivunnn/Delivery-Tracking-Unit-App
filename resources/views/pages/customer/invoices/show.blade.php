@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.invoices.index') }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
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
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
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
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $invoice->order->unit->tipe_motor }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $invoice->order->unit->no_rangka }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Warna</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->unit->warna }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tahun</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->unit->tahun ?? '-' }}</dd>
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
                        <div class="w-8 h-8 rounded-full bg-success-50 dark:bg-success-500/15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                        <div class="w-8 h-8 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $invoice->order->kode_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tanggal Invoice</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Cetak --}}
            <a href="{{ route('customer.invoices.print', $invoice) }}" target="_blank"
                class="flex items-center justify-center gap-2 w-full rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Invoice
            </a>

        </div>
    </div>

</div>
@endsection