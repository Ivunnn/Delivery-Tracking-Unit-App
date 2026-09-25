@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                Selamat datang, {{ $user->name }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Pantau order dan status pengiriman unit kamu dari satu halaman.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Order</p>
                <p class="mt-1 text-3xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalOrders) }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Order Aktif</p>
                <p class="mt-1 text-3xl font-bold text-brand-500">
                    {{ number_format($activeOrders) }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Order Selesai</p>
                <p class="mt-1 text-3xl font-bold text-success-500">
                    {{ number_format($completedOrders) }}
                </p>
            </div>
            <div class="col-span-2 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:col-span-1">
                <p class="text-xs text-gray-500 dark:text-gray-400">Invoice Belum Bayar</p>
                <p class="mt-1 text-3xl font-bold text-warning-500">
                    {{ number_format($unpaidInvoices) }}
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-gray-800">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Order Terbaru</h3>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Lima order terakhir kamu</p>
                </div>
                <a href="{{ route('customer.orders.index') }}"
                    class="text-xs font-medium text-brand-500 hover:text-brand-600">
                    Lihat semua
                </a>
            </div>

            @forelse ($recentOrders as $order)
                @php
                    $badge = $order->status_badge;
                    $statusColors = [
                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                    ];
                @endphp
                <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 last:border-0 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-gray-800">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $order->kode_order }}
                            </p>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$badge['color']] ?? $statusColors['default'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                        <p class="mt-1 truncate text-sm text-gray-600 dark:text-gray-400">
                            {{ $order->unit?->tipe_motor ?? 'Unit tidak tersedia' }}
                            @if ($order->unit?->warna)
                                · {{ $order->unit->warna }}
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            {{ $order->created_at->format('d M Y') }}
                            @if ($order->pengiriman)
                                · Pengiriman {{ $order->pengiriman->status_badge['label'] }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center justify-between gap-4 sm:justify-end">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ $order->invoice?->total_format ?? $order->unit?->harga_format ?? '-' }}
                        </p>
                        <a href="{{ route('customer.orders.show', $order) }}"
                            class="shrink-0 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada order.</p>
                    <a href="{{ route('customer.katalog.index') }}"
                        class="mt-3 inline-flex text-sm font-medium text-brand-500 hover:text-brand-600">
                        Lihat katalog unit
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
