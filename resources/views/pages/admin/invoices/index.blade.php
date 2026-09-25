@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Invoice</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola invoice dan konfirmasi pembayaran</p>
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
        <form method="GET" action="{{ route('admin.invoices.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode invoice, order, customer..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <select name="status"
                class="rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Semua Status</option>
                <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="sudah_bayar" {{ request('status') === 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
            </select>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Cari
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.invoices.index') }}"
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
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Kode Invoice</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Customer</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Unit</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Total</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $invoices->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-medium text-gray-800 dark:text-white/90">
                                {{ $invoice->kode_invoice }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $invoice->order->customer->name }}
                                </div>
                                @if ($invoice->order->customer->nama_toko)
                                    <div class="text-xs text-gray-400">{{ $invoice->order->customer->nama_toko }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">
                                    {{ $invoice->order->unit->tipe_motor }}
                                </div>
                                <div class="text-xs text-gray-400">{{ $invoice->order->unit->warna }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white/90">
                                {{ $invoice->total_format }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $invoice->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($invoice->status_bayar === 'sudah_bayar')
                                    <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        Detail
                                    </a>
                                    @if ($invoice->status_bayar === 'belum_bayar')
                                        <form method="POST"
                                            action="{{ route('admin.invoices.konfirmasi-bayar', $invoice) }}"
                                            onsubmit="return confirm('Konfirmasi pembayaran invoice {{ $invoice->kode_invoice }}?')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-success-200 px-3 py-1.5 text-xs font-medium text-success-600 hover:bg-success-50 transition dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/15">
                                                Konfirmasi Bayar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada invoice</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection