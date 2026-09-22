@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Kelola Order</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Daftar order yang masuk dari customer
            </p>
        </div>
        {{-- Badge ringkasan --}}
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-warning-50 px-3 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">
                {{ \App\Models\Order::menunggu()->count() }} Menunggu
            </span>
        </div>
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
            <svg class="w-5 h-5 text-error-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filter & Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('admin.orders.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode order, nama customer, tipe motor..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <select name="status"
                class="rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Semua Status</option>
                <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Cari
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}"
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
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Kode Order</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Customer</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Unit</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Harga</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($orders as $order)
                        @php $badge = $order->status_badge; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $orders->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-medium text-gray-800 dark:text-white/90">
                                {{ $order->kode_order }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $order->customer->name }}
                                </div>
                                @if($order->customer->nama_toko)
                                    <div class="text-xs text-gray-400">{{ $order->customer->nama_toko }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $order->unit->tipe_motor }}
                                </div>
                                <div class="text-xs text-gray-400 font-mono">{{ $order->unit->no_rangka }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $order->unit->harga_format ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                        'error'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$badge['color']] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}"
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
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada order masuk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection