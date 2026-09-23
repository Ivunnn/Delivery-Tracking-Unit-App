@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Kelola Pengiriman</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar seluruh pengiriman unit</p>
        </div>
        <a href="{{ route('admin.pengiriman.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengiriman
        </a>
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

    {{-- Filter & Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('admin.pengiriman.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode, customer, driver, tujuan..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <select name="status"
                class="rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Semua Status</option>
                <option value="menunggu"         {{ request('status') === 'menunggu'         ? 'selected' : '' }}>Menunggu</option>
                <option value="berangkat"        {{ request('status') === 'berangkat'        ? 'selected' : '' }}>Berangkat</option>
                <option value="dalam_perjalanan" {{ request('status') === 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                <option value="tiba"             {{ request('status') === 'tiba'             ? 'selected' : '' }}>Tiba</option>
                <option value="selesai"          {{ request('status') === 'selesai'          ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Cari
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.pengiriman.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Customer</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Unit</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Driver</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Tgl Kirim</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Estimasi Tiba</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($pengiriman as $item)
                        @php $badge = $item->status_badge; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $pengiriman->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-medium text-gray-800 dark:text-white/90">
                                {{ $item->kode_pengiriman }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $item->order->customer->name }}
                                </div>
                                @if ($item->order->customer->nama_toko)
                                    <div class="text-xs text-gray-400">{{ $item->order->customer->nama_toko }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $item->order->unit->tipe_motor }}
                                </div>
                                <div class="text-xs text-gray-400">{{ $item->order->unit->warna }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->driver->user->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->tanggal_kirim->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->estimasi_tiba?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.pengiriman.show', $item) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada data pengiriman</p>
                                    <a href="{{ route('admin.pengiriman.create') }}"
                                        class="text-sm text-brand-500 hover:text-brand-600 font-medium">
                                        Buat pengiriman pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengiriman->hasPages())
            <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                {{ $pengiriman->links() }}
            </div>
        @endif
    </div>

</div>
@endsection