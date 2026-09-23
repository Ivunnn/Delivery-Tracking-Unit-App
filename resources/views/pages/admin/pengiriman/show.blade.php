@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pengiriman.index') }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Pengiriman</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">
                    {{ $pengiriman->kode_pengiriman }}
                </p>
            </div>
        </div>
        {{-- Badge Status --}}
        @php
            $badge  = $pengiriman->status_badge;
            $colors = [
                'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                'info'    => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
            ];
        @endphp
        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
            {{ $badge['label'] }}
        </span>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
            <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Pengiriman --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Pengiriman</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Kode Pengiriman</dt>
                        <dd class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">{{ $pengiriman->kode_pengiriman }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kirim</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->tanggal_kirim->format('d M Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Estimasi Tiba</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->estimasi_tiba?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 shrink-0">Tujuan</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90 text-right">{{ $pengiriman->tujuan }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Order</dt>
                        <dd>
                            <a href="{{ route('admin.orders.show', $pengiriman->order) }}"
                                class="text-sm font-mono text-brand-500 hover:text-brand-600">
                                {{ $pengiriman->order->kode_order }}
                            </a>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Timeline Tracking --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Timeline Tracking</h3>

                @if ($pengiriman->trackings->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-600">
                        Belum ada update lokasi dari driver.
                    </p>
                @else
                    <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-6 ml-3">
                        @foreach ($pengiriman->trackings as $track)
                            <li class="ml-6">
                                <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 ring-8 ring-white dark:ring-gray-900 dark:bg-brand-900">
                                    <svg class="w-3 h-3 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                            {{ $track->status_tracking }}
                                        </p>
                                        @if ($track->lokasi)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                📍 {{ $track->lokasi }}
                                            </p>
                                        @endif
                                        @if ($track->catatan)
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                                {{ $track->catatan }}
                                            </p>
                                        @endif
                                    </div>
                                    <time class="text-xs text-gray-400 dark:text-gray-500 shrink-0 ml-4">
                                        {{ $track->jam_update->format('d M Y, H:i') }}
                                    </time>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>

            {{-- Bukti Pengiriman --}}
            @if ($pengiriman->buktiPengiriman)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Bukti Pengiriman</h3>
                    <div class="space-y-3">
                        <img src="{{ asset('storage/' . $pengiriman->buktiPengiriman->foto_bukti) }}"
                            alt="Bukti Pengiriman"
                            class="w-full max-w-sm rounded-lg border border-gray-200 dark:border-gray-700" />
                        @if ($pengiriman->buktiPengiriman->keterangan)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $pengiriman->buktiPengiriman->keterangan }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-400">
                            Diupload: {{ $pengiriman->buktiPengiriman->waktu_upload->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
            @endif

        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">

            {{-- Info Driver --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Driver</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Nama</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $pengiriman->driver->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">No. HP</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->driver->user->phone ?? '-' }}</dd>
                    </div>
                    @if ($pengiriman->driver->no_sim)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">No. SIM</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->driver->no_sim }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Info Customer & Unit --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Customer</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Nama</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $pengiriman->order->customer->name }}</dd>
                    </div>
                    @if ($pengiriman->order->customer->nama_toko)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Nama Toko</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->order->customer->nama_toko }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">No. HP</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->order->customer->phone ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Info Unit --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tipe Motor</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $pengiriman->order->unit->tipe_motor }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $pengiriman->order->unit->no_rangka }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Warna</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->order->unit->warna }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Invoice --}}
            @if ($pengiriman->order->invoice)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Invoice</h3>
                    <p class="text-sm font-mono text-gray-800 dark:text-white/90 mb-1">
                        {{ $pengiriman->order->invoice->kode_invoice }}
                    </p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                        {{ $pengiriman->order->invoice->total_format }}
                    </p>
                    <p class="text-xs mt-1 {{ $pengiriman->order->invoice->status_bayar === 'sudah_bayar' ? 'text-success-500' : 'text-warning-500' }}">
                        {{ $pengiriman->order->invoice->status_bayar === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection