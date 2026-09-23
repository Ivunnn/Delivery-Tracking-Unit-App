@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Order Saya</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar order yang sudah kamu ajukan</p>
        </div>
        <a href="{{ route('customer.katalog.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Order Unit Baru
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

    {{-- Filter Status --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('customer.orders.index') }}"
            class="flex flex-wrap gap-2">
            @php
                $statuses = [
                    ''          => 'Semua',
                    'menunggu'  => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'ditolak'   => 'Ditolak',
                    'selesai'   => 'Selesai',
                ];
            @endphp
            @foreach ($statuses as $val => $label)
                <button type="submit" name="status" value="{{ $val }}"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition
                    {{ request('status', '') === $val
                        ? 'bg-brand-500 text-white'
                        : 'border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </form>
    </div>

    {{-- List Order --}}
    <div class="space-y-4">
        @forelse ($orders as $order)
            @php
                $badge  = $order->status_badge;
                $colors = [
                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                    'error'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                    'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                ];
            @endphp
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">

                        {{-- Kode & Status --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90">
                                {{ $order->kode_order }}
                            </span>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] }}">
                                {{ $badge['label'] }}
                            </span>
                            @if ($order->invoice)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $order->invoice->status_bayar === 'sudah_bayar'
                                        ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'
                                        : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $order->invoice->status_bayar === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar' }}
                                </span>
                            @endif
                        </div>

                        {{-- Unit --}}
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $order->unit->tipe_motor }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $order->unit->warna }}
                                @if ($order->unit->tahun) · {{ $order->unit->tahun }} @endif
                                · <span class="font-mono">{{ $order->unit->no_rangka }}</span>
                            </p>
                        </div>

                        {{-- Harga --}}
                        @if ($order->unit->harga)
                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $order->unit->harga_format }}
                            </p>
                        @endif

                        {{-- Info Pengiriman --}}
                        @if ($order->pengiriman)
                            <p class="text-xs text-brand-500">
                                🚚 {{ $order->pengiriman->kode_pengiriman }}
                                · {{ $order->pengiriman->status_badge['label'] }}
                            </p>
                        @endif

                        {{-- Alasan tolak --}}
                        @if ($order->alasan_tolak)
                            <p class="text-xs text-error-500">
                                Alasan: {{ $order->alasan_tolak }}
                            </p>
                        @endif

                        <p class="text-xs text-gray-400">
                            Diajukan {{ $order->created_at->diffForHumans() }}
                        </p>

                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        <a href="{{ route('customer.orders.show', $order) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                            Detail
                        </a>
                        @if ($order->status === 'menunggu')
                            <form method="POST"
                                action="{{ route('customer.orders.cancel', $order) }}"
                                onsubmit="return confirm('Yakin ingin membatalkan order ini?')">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-error-200 px-3 py-1.5 text-xs font-medium text-error-600 hover:bg-error-50 transition dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/15">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-400 dark:text-gray-600 mb-3">Belum ada order</p>
                <a href="{{ route('customer.katalog.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    Lihat Katalog Unit
                </a>
            </div>
        @endforelse
    </div>

    @if ($orders->hasPages())
        <div>{{ $orders->links() }}</div>
    @endif

</div>
@endsection