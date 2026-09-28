@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Monitoring Tracking</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pantau pengiriman yang sedang aktif secara real-time
            </p>
        </div>

        {{-- Filter & Search --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <form method="GET" action="{{ route('admin.monitoring.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">

                {{-- Search Driver --}}
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama driver, kode pengiriman, customer..."
                        class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                {{-- Filter Status --}}
                <select name="status"
                    class="rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Semua Status</option>
                    <option value="berangkat" {{ request('status') === 'berangkat' ? 'selected' : '' }}>Berangkat</option>
                    <option value="dalam_perjalanan" {{ request('status') === 'dalam_perjalanan' ? 'selected' : '' }}>Dalam
                        Perjalanan</option>
                    <option value="tiba" {{ request('status') === 'tiba' ? 'selected' : '' }}>Tiba di Lokasi</option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    Cari
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.monitoring.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        Reset
                    </a>
                @endif

            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <strong class="text-gray-800 dark:text-white/90">{{ $pengiriman->count() }}</strong>
                pengiriman aktif saat ini
            </p>
        </div>

        {{-- List Pengiriman --}}
        @if ($pengiriman->isEmpty())
            <div
                class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-sm text-gray-400 dark:text-gray-600">
                    Tidak ada pengiriman aktif saat ini
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pengiriman as $item)
                    @php
                        $badge = $item->status_badge;
                        $colors = [
                            'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                            'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                            'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        ];
                        $tracking = $item->trackingTerakhir;
                        $adaGps = $tracking && $tracking->lat && $tracking->lng;
                    @endphp

                    <a href="{{ route('admin.monitoring.show', $item) }}"
                        class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700 transition block">

                        {{-- Header Card --}}
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90">
                                    {{ $item->kode_pengiriman }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->tanggal_kirim->format('d M Y') }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        {{-- Unit & Customer --}}
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2">
                                {{-- Foto Unit --}}
                                <div
                                    class="w-8 h-8 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700 shrink-0 bg-gray-50 dark:bg-gray-800">
                                    @if ($item->order->unit->foto)
                                        <img src="{{ $item->order->unit->foto_url }}" alt="{{ $item->order->unit->tipe_motor }}"
                                            class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
                                        {{ $item->order->unit->tipe_motor }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $item->order->unit->warna }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Customer: {{ $item->order->customer->name }}
                                @if ($item->order->customer->nama_toko)
                                    · {{ $item->order->customer->nama_toko }}
                                @endif
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                📍 {{ $item->tujuan }}
                            </p>
                        </div>

                        {{-- Driver --}}
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                            <div
                                class="w-7 h-7 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item->driver->user->name }}
                                </p>
                                @if ($tracking)
                                    <p class="text-xs text-brand-500">
                                        {{ $tracking->status_tracking }}
                                        · {{ $tracking->jam_update->diffForHumans() }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400">Belum ada update</p>
                                @endif
                            </div>

                            {{-- Indikator GPS --}}
                            @if ($adaGps)
                                <span class="flex items-center gap-1 text-xs text-success-500 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                                    GPS
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-xs text-gray-400 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                    No GPS
                                </span>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <div class="mt-3 flex items-center justify-end gap-1 text-xs text-brand-500 font-medium">
                            Pantau detail
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                    </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection