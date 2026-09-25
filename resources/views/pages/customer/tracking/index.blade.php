@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Tracking Pengiriman</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pantau status pengiriman unit kamu
            </p>
        </div>

        <div class="space-y-4">
            @forelse ($pengiriman as $item)
                @php $badge = $item->status_badge; @endphp
                @php
                    $colors = [
                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                        'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                    ];
                @endphp
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2 flex-1">

                            {{-- Kode & Status --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90">
                                    {{ $item->kode_pengiriman }}
                                </span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $item->order->unit->tipe_motor }}
                                    · {{ $item->order->unit->warna }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $item->order->unit->no_rangka }}
                                </p>
                            </div>

                            {{-- Driver --}}
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Driver: {{ $item->driver->user->name }}
                                @if ($item->driver->user->phone)
                                    · {{ $item->driver->user->phone }}
                                @endif
                            </p>

                            {{-- Tujuan --}}
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                📍 {{ $item->tujuan }}
                            </p>

                            {{-- Tanggal --}}
                            <p class="text-xs text-gray-400">
                                Tanggal kirim:
                                {{ $item->tanggal_kirim?->format('d M Y') ?? 'Belum ditentukan' }}
                                @if ($item->estimasi_tiba)
                                    · Estimasi tiba: {{ $item->estimasi_tiba->format('d M Y') }}
                                @endif
                            </p>

                            {{-- Update terakhir --}}
                            @if ($item->latestTracking?->jam_update)
                                <p class="text-xs text-brand-500">
                                    Update terakhir: {{ $item->latestTracking->status_tracking }}
                                    · {{ $item->latestTracking->jam_update->diffForHumans() }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">Belum ada update dari driver</p>
                            @endif

                        </div>

                        {{-- Aksi --}}
                        <div class="shrink-0">
                            @if ($item->status !== 'selesai')
                                <a href="{{ route('customer.tracking.show', $item) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white hover:bg-brand-600 transition">
                                    Pantau
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('customer.tracking.show', $item) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                    Lihat Riwayat
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div
                    class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-gray-400 dark:text-gray-600 mb-1">
                        Belum ada pengiriman
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-600">
                        Pengiriman akan muncul setelah order kamu disetujui dan diproses admin
                    </p>
                </div>
            @endforelse
        </div>

        @if ($pengiriman->hasPages())
            <div>{{ $pengiriman->links() }}</div>
        @endif

    </div>
@endsection