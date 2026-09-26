@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    Selamat datang, {{ Auth::user()->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            {{-- Status Driver --}}
            <div class="shrink-0">
                @if ($driver->status === 'tersedia')
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-success-50 px-3 py-1.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                        <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                        Tersedia
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-warning-50 px-3 py-1.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">
                        <span class="w-2 h-2 rounded-full bg-warning-500 animate-pulse"></span>
                        Sedang Bertugas
                    </span>
                @endif
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-3 gap-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div
                    class="w-10 h-10 rounded-xl bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Aktif</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalAktif }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div
                    class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                <p class="text-3xl font-bold text-success-500 mt-1">{{ $totalSelesai }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalSemua }}</p>
            </div>

        </div>

        {{-- Pengiriman Aktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengiriman Aktif</h3>
                <a href="{{ route('driver.pengiriman.aktif') }}" class="text-xs text-brand-500 hover:text-brand-600">Lihat
                    semua</a>
            </div>

            @forelse ($aktifHariIni as $item)
                @php
                    $badge = $item->status_badge;
                    $colors = [
                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                        'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                    ];
                @endphp
                <a href="{{ route('driver.pengiriman.show', $item) }}"
                    class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">

                    {{-- Icon --}}
                    <div
                        class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                                {{ $item->kode_pengiriman }}
                            </p>
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                            {{ $item->order->unit->tipe_motor }}
                            · {{ $item->order->unit->warna }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            📍 {{ $item->tujuan }}
                        </p>
                        @if ($item->trackingTerakhir)
                            <p class="text-xs text-brand-500 mt-0.5">
                                Update: {{ $item->trackingTerakhir->status_tracking }}
                                · {{ $item->trackingTerakhir->jam_update->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>

                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada pengiriman aktif</p>
                </div>
            @endforelse
        </div>

        {{-- Riwayat Terakhir --}}
        @if ($riwayatTerakhir->isNotEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Riwayat Terakhir</h3>
                    <a href="{{ route('driver.pengiriman.riwayat') }}" class="text-xs text-brand-500 hover:text-brand-600">Lihat
                        semua</a>
                </div>

                @foreach ($riwayatTerakhir as $item)
                    <a href="{{ route('driver.pengiriman.show', $item) }}"
                        class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">

                        <div
                            class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                                {{ $item->kode_pengiriman }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $item->order->unit->tipe_motor }}
                                · {{ $item->order->customer->name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $item->updated_at->format('d M Y') }}
                            </p>
                        </div>

                        <span
                            class="inline-flex items-center rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400 shrink-0">
                            Selesai
                        </span>

                    </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection