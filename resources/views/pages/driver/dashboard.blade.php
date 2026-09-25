@extends('layouts.app')

@section('content')
<div class="space-y-5 sm:space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold leading-tight text-gray-800 dark:text-white/90 sm:text-2xl">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Status kamu saat ini:
            @if ($driver->status === 'tersedia')
                <span class="text-success-500 font-medium">Tersedia</span>
            @else
                <span class="text-warning-500 font-medium">Sedang Bertugas</span>
            @endif
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 sm:p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400">Pengiriman Aktif</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90 sm:text-3xl">{{ $aktif->count() }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 sm:p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
            <p class="mt-1 text-2xl font-bold text-success-500 sm:text-3xl">{{ $totalSelesai }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 sm:p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Pengiriman</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90 sm:text-3xl">{{ $totalSemua }}</p>
        </div>
    </div>

    {{-- Pengiriman Aktif --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-4 py-4 dark:border-gray-800 sm:px-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengiriman Aktif</h3>
            <a href="{{ route('driver.pengiriman.aktif') }}"
                class="shrink-0 text-xs text-brand-500 hover:text-brand-600">Lihat semua</a>
        </div>

        @forelse ($aktif as $item)
            @php $badge = $item->status_badge; @endphp
            <div class="flex flex-col gap-3 border-b border-gray-50 px-4 py-4 dark:border-gray-800 last:border-0 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="min-w-0 space-y-1">
                    <p class="truncate font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                        {{ $item->kode_pengiriman }}
                    </p>
                    <p class="break-words text-xs text-gray-500 dark:text-gray-400">
                        {{ $item->order->unit->tipe_motor }} — {{ $item->order->customer->name }}
                    </p>
                    <p class="break-words text-xs text-gray-400">📍 {{ $item->tujuan }}</p>
                </div>
                <div class="flex items-center justify-between gap-3 sm:flex-col sm:items-end sm:justify-start">
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
                        Buka <span class="rtl:inline-block rtl:-scale-x-100">→</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="px-4 py-10 text-center sm:px-6 sm:py-12">
                <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada pengiriman aktif</p>
            </div>
        @endforelse
    </div>

</div>
@endsection