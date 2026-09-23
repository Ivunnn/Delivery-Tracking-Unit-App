@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Status kamu saat ini:
            @if ($driver->status === 'tersedia')
                <span class="text-success-500 font-medium">Tersedia</span>
            @else
                <span class="text-warning-500 font-medium">Sedang Bertugas</span>
            @endif
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Pengiriman Aktif</p>
            <p class="mt-1 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $aktif->count() }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
            <p class="mt-1 text-3xl font-bold text-success-500">{{ $totalSelesai }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 col-span-2 sm:col-span-1">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Pengiriman</p>
            <p class="mt-1 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $totalSemua }}</p>
        </div>
    </div>

    {{-- Pengiriman Aktif --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengiriman Aktif</h3>
            <a href="{{ route('driver.pengiriman.aktif') }}"
                class="text-xs text-brand-500 hover:text-brand-600">Lihat semua</a>
        </div>

        @forelse ($aktif as $item)
            @php $badge = $item->status_badge; @endphp
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 dark:border-gray-800 last:border-0">
                <div class="space-y-1">
                    <p class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                        {{ $item->kode_pengiriman }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $item->order->unit->tipe_motor }} — {{ $item->order->customer->name }}
                    </p>
                    <p class="text-xs text-gray-400">📍 {{ $item->tujuan }}</p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    @php
                        $colors = [
                            'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            'info'    => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                            'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                            'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                        {{ $badge['label'] }}
                    </span>
                    <a href="{{ route('driver.pengiriman.show', $item) }}"
                        class="text-xs text-brand-500 hover:text-brand-600 font-medium">
                        Buka →
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada pengiriman aktif</p>
            </div>
        @endforelse
    </div>

</div>
@endsection