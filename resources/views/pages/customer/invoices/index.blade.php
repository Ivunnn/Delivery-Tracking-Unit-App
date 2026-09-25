@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Invoice Saya</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Daftar invoice dari order yang sudah disetujui
        </p>
    </div>

    {{-- Filter --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('customer.invoices.index') }}"
            class="flex flex-wrap gap-2">
            @foreach (['' => 'Semua', 'belum_bayar' => 'Belum Bayar', 'sudah_bayar' => 'Lunas'] as $val => $label)
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

    {{-- List Invoice --}}
    <div class="space-y-4">
        @forelse ($invoices as $invoice)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900
                {{ $invoice->status_bayar === 'belum_bayar' ? 'border-l-4 border-l-warning-400' : 'border-l-4 border-l-success-400' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">

                        {{-- Kode & Status --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90">
                                {{ $invoice->kode_invoice }}
                            </span>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $invoice->status_bayar === 'sudah_bayar'
                                    ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'
                                    : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' }}">
                                {{ $invoice->status_bayar === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar' }}
                            </span>
                        </div>

                        {{-- Unit --}}
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $invoice->order->unit->tipe_motor }}
                            <span class="text-gray-400 font-normal">·</span>
                            {{ $invoice->order->unit->warna }}
                        </p>

                        {{-- Total --}}
                        <p class="text-lg font-bold text-gray-800 dark:text-white/90">
                            {{ $invoice->total_format }}
                        </p>

                        <p class="text-xs text-gray-400">
                            Dibuat {{ $invoice->created_at->format('d M Y') }}
                            @if ($invoice->paid_at)
                                · Dibayar {{ $invoice->paid_at->format('d M Y') }}
                            @endif
                        </p>

                    </div>

                    {{-- Aksi --}}
                    <a href="{{ route('customer.invoices.show', $invoice) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada invoice</p>
                <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">
                    Invoice akan muncul setelah order disetujui admin
                </p>
            </div>
        @endforelse
    </div>

    @if ($invoices->hasPages())
        <div>{{ $invoices->links() }}</div>
    @endif

</div>
@endsection