@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.orders.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Order</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $order->kode_order }}</p>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
            <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Progress Stepper --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between relative">
            {{-- Garis --}}
            <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-100 dark:bg-gray-800 z-0"></div>
            @php
                $steps = [
                    ['key' => 'menunggu',  'label' => 'Order\nDiajukan'],
                    ['key' => 'disetujui', 'label' => 'Disetujui'],
                    ['key' => 'kirim',     'label' => 'Dikirim'],
                    ['key' => 'selesai',   'label' => 'Selesai'],
                ];
                $activeMap = [
                    'menunggu'  => 0,
                    'disetujui' => 1,
                    'ditolak'   => 0,
                    'selesai'   => 3,
                ];
                $activeStep = $activeMap[$order->status] ?? 0;
                if ($order->pengiriman && in_array($order->pengiriman->status, ['berangkat','dalam_perjalanan','tiba'])) {
                    $activeStep = 2;
                }
            @endphp
            @foreach ($steps as $i => $step)
                <div class="flex flex-col items-center z-10 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $i <= $activeStep
                            ? 'bg-brand-500 text-white'
                            : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600' }}">
                        @if ($i < $activeStep)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <p class="text-xs text-center mt-2 text-gray-500 dark:text-gray-400 whitespace-pre-line leading-tight">
                        {{ $step['label'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Ditolak --}}
        @if ($order->status === 'ditolak')
            <div class="mt-4 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
                <p class="text-sm font-medium text-error-700 dark:text-error-400">Order Ditolak</p>
                @if ($order->alasan_tolak)
                    <p class="text-xs text-error-600 dark:text-error-500 mt-0.5">{{ $order->alasan_tolak }}</p>
                @endif
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Detail Unit --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit yang Dipesan</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Motor</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $order->unit->tipe_motor }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $order->unit->no_rangka }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Warna</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->unit->warna }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tahun</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $order->unit->tahun ?? '-' }}</dd>
                    </div>
                    @if ($order->unit->harga)
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                            <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">Harga</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $order->unit->harga_format }}</dd>
                        </div>
                    @endif
                </dl>
                @if ($order->catatan)
                    <div class="mt-4 rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                        <p class="text-xs text-gray-400 mb-1">Catatan kamu</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->catatan }}</p>
                    </div>
                @endif
            </div>

            {{-- Info Pengiriman --}}
            @if ($order->pengiriman)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengiriman</h3>
                        <a href="{{ route('customer.tracking.show', $order->pengiriman) }}"
                            class="text-xs text-brand-500 hover:text-brand-600 font-medium">
                            Pantau Tracking →
                        </a>
                    </div>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Kode Pengiriman</dt>
                            <dd class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                                {{ $order->pengiriman->kode_pengiriman }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                @php
                                    $pb = $order->pengiriman->status_badge;
                                    $pc = [
                                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                        'info'    => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $pc[$pb['color']] ?? $pc['default'] }}">
                                    {{ $pb['label'] }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kirim</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                {{ $order->pengiriman->tanggal_kirim->format('d M Y') }}
                            </dd>
                        </div>
                        @if ($order->pengiriman->estimasi_tiba)
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Estimasi Tiba</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $order->pengiriman->estimasi_tiba->format('d M Y') }}
                                </dd>
                            </div>
                        @endif
                        @if ($order->pengiriman->trackingTerakhir)
                            <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Update Terakhir</dt>
                                <dd class="text-sm text-brand-500">
                                    {{ $order->pengiriman->trackingTerakhir->status_tracking }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">

            {{-- Status Order --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status Order</h3>
                @php
                    $badge  = $order->status_badge;
                    $colors = [
                        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        'error'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                        'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                    ];
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $colors[$badge['color']] }}">
                    {{ $badge['label'] }}
                </span>
                <p class="text-xs text-gray-400 mt-2">
                    Diajukan {{ $order->created_at->format('d M Y, H:i') }}
                </p>
                @if ($order->approved_at)
                    <p class="text-xs text-gray-400 mt-1">
                        Disetujui {{ $order->approved_at->format('d M Y, H:i') }}
                    </p>
                @endif

                @if ($order->status === 'menunggu')
                    <form method="POST"
                        action="{{ route('customer.orders.cancel', $order) }}"
                        onsubmit="return confirm('Yakin ingin membatalkan order ini?')"
                        class="mt-4">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg border border-error-200 px-4 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50 transition dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/15">
                            Batalkan Order
                        </button>
                    </form>
                @endif
            </div>

            {{-- Invoice --}}
            @if ($order->invoice)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Invoice</h3>
                        <a href="{{ route('customer.invoices.show', $order->invoice) }}"
                            class="text-xs text-brand-500 hover:text-brand-600">Lihat →</a>
                    </div>
                    <p class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $order->invoice->kode_invoice }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white/90 mt-1">{{ $order->invoice->total_format }}</p>
                    <span class="inline-flex items-center rounded-full mt-2 px-2.5 py-0.5 text-xs font-medium
                        {{ $order->invoice->status_bayar === 'sudah_bayar'
                            ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'
                            : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' }}">
                        {{ $order->invoice->status_bayar === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection