@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Pengiriman Aktif</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Daftar pengiriman yang sedang berjalan
        </p>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
            <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($pengiriman as $item)
            @php $badge = $item->status_badge; @endphp
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90">
                                {{ $item->kode_pengiriman }}
                            </span>
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
                        </div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $item->order->unit->tipe_motor }}
                            <span class="text-gray-400">·</span>
                            {{ $item->order->unit->warna }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Customer: {{ $item->order->customer->name }}
                            @if($item->order->customer->nama_toko)
                                ({{ $item->order->customer->nama_toko }})
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            📍 {{ $item->tujuan }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Tanggal kirim: {{ $item->tanggal_kirim->format('d M Y') }}
                            @if ($item->estimasi_tiba)
                                · Estimasi tiba: {{ $item->estimasi_tiba->format('d M Y') }}
                            @endif
                        </p>
                        @if ($item->trackingTerakhir)
                            <p class="text-xs text-brand-500">
                                Update terakhir: {{ $item->trackingTerakhir->status_tracking }}
                                · {{ $item->trackingTerakhir->jam_update->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                    <a href="{{ route('driver.pengiriman.show', $item) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white hover:bg-brand-600 transition shrink-0">
                        Buka
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada pengiriman aktif saat ini</p>
            </div>
        @endforelse
    </div>

    @if ($pengiriman->hasPages())
        <div>{{ $pengiriman->links() }}</div>
    @endif

</div>
@endsection