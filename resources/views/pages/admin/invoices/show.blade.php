@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.invoices.index') }}"
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
            <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
                <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="flex items-center gap-3 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
                <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') }}</p>
            </div>
        @endif

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

                {{-- Update Biaya Pengiriman --}}
                @if ($invoice->status_bayar === 'belum_bayar')
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                            Update Biaya Pengiriman
                        </h3>
                        <form method="POST" action="{{ route('admin.invoices.update-biaya', $invoice) }}"
                            class="flex items-end gap-3">
                            @csrf
                            <div class="flex-1">
                                <label for="biaya_pengiriman"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Biaya Pengiriman (Rp)
                                </label>
                                <input type="number" id="biaya_pengiriman" name="biaya_pengiriman"
                                    value="{{ old('biaya_pengiriman', $invoice->biaya_pengiriman) }}" min="0" placeholder="0"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                                        {{ $errors->has('biaya_pengiriman') ? 'border-error-400' : '' }}" />
                                @error('biaya_pengiriman')
                                    <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="h-11 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600 transition shrink-0">
                                Update
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Info Unit --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit</h3>
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

            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-6">

                {{-- Status Pembayaran --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status Pembayaran</h3>

                    @if ($invoice->status_bayar === 'sudah_bayar')
                        <div class="flex items-center gap-2 mb-3">
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
                        <div class="flex items-center gap-2 mb-4">
                            <div
                                class="w-8 h-8 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-warning-600 dark:text-warning-400">Belum Bayar</p>
                        </div>

                        <form method="POST" action="{{ route('admin.invoices.konfirmasi-bayar', $invoice) }}"
                            onsubmit="return confirm('Konfirmasi pembayaran invoice {{ $invoice->kode_invoice }}?')">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-success-600 transition">
                                ✓ Konfirmasi Sudah Bayar
                            </button>
                        </form>
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
                            <dd>
                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                    class="text-sm font-mono text-brand-500 hover:text-brand-600">
                                    {{ $invoice->order->kode_order }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Tanggal Invoice</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->created_at->format('d M Y') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Info Customer --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Customer</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Nama</dt>
                            <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $invoice->order->customer->name }}</dd>
                        </div>
                        @if ($invoice->order->customer->nama_toko)
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Nama Toko</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->customer->nama_toko }}
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Email</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $invoice->order->customer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">No. HP</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                {{ $invoice->order->customer->phone ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>

        {{-- Verifikasi Bukti Transfer --}}
        @if ($invoice->bukti_bayar)
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Bukti Transfer</h3>

                @php
                    $vBadge = $invoice->status_verifikasi_badge;
                    $vColors = [
                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                    ];
                @endphp

                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium mb-3 {{ $vColors[$vBadge['color']] }}">
                    {{ $vBadge['label'] }}
                </span>

                @if ($invoice->tgl_upload_bukti)
                    <p class="text-xs text-gray-400 mb-3">
                        Diupload: {{ $invoice->tgl_upload_bukti->format('d M Y, H:i') }}
                    </p>
                @endif

                <img src="{{ asset('storage/' . $invoice->bukti_bayar) }}" alt="Bukti Transfer"
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-700 mb-4" />

                @if ($invoice->status_verifikasi === 'menunggu_verifikasi')
                    <div class="space-y-2">
                        {{-- Terima --}}
                        <form method="POST" action="{{ route('admin.invoices.verifikasi-bukti', $invoice) }}"
                            onsubmit="return confirm('Konfirmasi bukti transfer diterima?')">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-success-600 transition">
                                ✓ Terima Bukti Transfer
                            </button>
                        </form>

                        {{-- Tolak --}}
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                class="w-full rounded-lg border border-error-200 px-4 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50 transition dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/15">
                                ✕ Tolak Bukti Transfer
                            </button>
                            <div x-show="open" x-transition class="mt-2">
                                <form method="POST" action="{{ route('admin.invoices.tolak-bukti', $invoice) }}">
                                    @csrf
                                    <textarea name="catatan_tolak" rows="2" required placeholder="Alasan penolakan..."
                                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 mb-2"></textarea>
                                    @error('catatan_tolak')
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

                @if ($invoice->catatan_tolak)
                    <div class="mt-3 rounded-lg bg-error-50 dark:bg-error-500/10 px-3 py-2">
                        <p class="text-xs text-error-600 dark:text-error-400">
                            Alasan tolak: {{ $invoice->catatan_tolak }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

    </div>
@endsection