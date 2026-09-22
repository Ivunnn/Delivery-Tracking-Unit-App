@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Order</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $order->kode_order }}</p>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
            <svg class="w-5 h-5 text-success-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
            <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri: Info Order + Aksi --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Order --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Order</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Kode Order</dt>
                        <dd class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">{{ $order->kode_order }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->created_at->format('d M Y, H:i') }} WIB</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                        <dd>
                            @php
                                $badge  = $order->status_badge;
                                $colors = [
                                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                    'error'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                                    'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] }}">
                                {{ $badge['label'] }}
                            </span>
                        </dd>
                    </div>
                    @if ($order->approved_at)
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Disetujui Pada</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->approved_at->format('d M Y, H:i') }} WIB</dd>
                        </div>
                    @endif
                    @if ($order->alasan_tolak)
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-sm text-gray-500 dark:text-gray-400 shrink-0">Alasan Tolak</dt>
                            <dd class="text-sm text-error-600 dark:text-error-400 text-right">{{ $order->alasan_tolak }}</dd>
                        </div>
                    @endif
                    @if ($order->catatan)
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-sm text-gray-500 dark:text-gray-400 shrink-0">Catatan Customer</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90 text-right">{{ $order->catatan }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Info Unit --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit yang Dipesan</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Motor</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $order->unit->tipe_motor }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $order->unit->no_rangka }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Warna</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->unit->warna }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tahun</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->unit->tahun ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                        <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">Harga</dt>
                        <dd class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $order->unit->harga_format ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Info Invoice (jika sudah ada) --}}
            @if ($order->invoice)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Invoice</h3>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Kode Invoice</dt>
                            <dd class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">{{ $order->invoice->kode_invoice }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Harga Unit</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">Rp {{ number_format($order->invoice->harga, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Biaya Pengiriman</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">Rp {{ number_format($order->invoice->biaya_pengiriman, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                            <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $order->invoice->total_format }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Status Bayar</dt>
                            <dd>
                                @if ($order->invoice->status_bayar === 'sudah_bayar')
                                    <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                        Sudah Bayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">
                                        Belum Bayar
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            @endif

        </div>

        {{-- Kolom Kanan: Info Customer + Tombol Aksi --}}
        <div class="space-y-6">

            {{-- Info Customer --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Customer</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Nama</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $order->customer->name }}</dd>
                    </div>
                    @if ($order->customer->nama_toko)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Nama Toko</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->customer->nama_toko }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Email</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->customer->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">No. HP</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->customer->phone ?? '-' }}</dd>
                    </div>
                    @if ($order->customer->kota)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Kota</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->customer->kota }}</dd>
                        </div>
                    @endif
                    @if ($order->customer->alamat)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Alamat</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->customer->alamat }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Tombol Aksi --}}
            @if ($order->status === 'menunggu')
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tindakan</h3>

                    {{-- Approve --}}
                    <form method="POST" action="{{ route('admin.orders.approve', $order) }}"
                        onsubmit="return confirm('Setujui order {{ $order->kode_order }}?')">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-success-600 transition">
                            ✓ Setujui Order
                        </button>
                    </form>

                    {{-- Reject --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full rounded-lg border border-error-200 px-4 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50 transition dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/15">
                            ✕ Tolak Order
                        </button>
                        <div x-show="open" x-transition class="mt-3">
                            <form method="POST" action="{{ route('admin.orders.reject', $order) }}">
                                @csrf
                                <textarea name="alasan_tolak" rows="3" required
                                    placeholder="Tuliskan alasan penolakan..."
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 mb-2">{{ old('alasan_tolak') }}</textarea>
                                @error('alasan_tolak')
                                    <p class="mb-2 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                    class="w-full rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600 transition">
                                    Konfirmasi Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Link ke Pengiriman --}}
            @if ($order->pengiriman)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pengiriman</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 font-mono">
                        {{ $order->pengiriman->kode_pengiriman }}
                    </p>
                    <a href="{{ route('admin.pengiriman.show', $order->pengiriman) }}"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        Lihat Detail Pengiriman
                    </a>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection