@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.katalog.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $unit->tipe_motor }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detail unit yang tersedia</p>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
            <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri: Detail Unit --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Placeholder Gambar --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="h-64 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center">
                    <svg class="w-32 h-32 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
            </div>

            {{-- Spesifikasi --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Spesifikasi Unit</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Motor</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $unit->tipe_motor }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Warna</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $unit->warna }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tahun</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $unit->tahun ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $unit->no_rangka }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                        <dd>
                            <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                Tersedia
                            </span>
                        </dd>
                    </div>
                    @if ($unit->keterangan)
                        <div class="flex items-start justify-between py-2 gap-4">
                            <dt class="text-sm text-gray-500 dark:text-gray-400 shrink-0">Keterangan</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90 text-right">{{ $unit->keterangan }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

        </div>

        {{-- Kolom Kanan: Harga + Form Order --}}
        <div class="space-y-6">

            {{-- Harga --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-400 mb-1">Harga Unit</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white/90">
                    {{ $unit->harga ? $unit->harga_format : 'Hubungi Admin' }}
                </p>
            </div>

            {{-- Form Order --}}
            @if ($sudahOrder)
                <div class="rounded-2xl border border-warning-200 bg-warning-50 p-6 dark:border-warning-500/30 dark:bg-warning-500/10">
                    <p class="text-sm font-medium text-warning-700 dark:text-warning-400 mb-1">
                        Order Sudah Diajukan
                    </p>
                    <p class="text-xs text-warning-600 dark:text-warning-500">
                        Kamu sudah memiliki order aktif untuk unit ini. Pantau statusnya di halaman Order Saya.
                    </p>
                    <a href="{{ route('customer.orders.index') }}"
                        class="mt-3 block w-full rounded-lg border border-warning-300 px-4 py-2.5 text-center text-sm font-medium text-warning-700 hover:bg-warning-100 transition dark:border-warning-500/30 dark:text-warning-400">
                        Lihat Order Saya
                    </a>
                </div>
            @else
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        Ajukan Order
                    </h3>
                    <form method="POST"
                        action="{{ route('customer.katalog.order', $unit) }}"
                        class="space-y-4">
                        @csrf

                        <div>
                            <label for="catatan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Catatan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                            </label>
                            <textarea id="catatan" name="catatan" rows="3"
                                placeholder="Tuliskan catatan atau pertanyaan untuk admin..."
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3 text-xs text-gray-500 dark:text-gray-400">
                            Order akan diproses oleh admin. Kamu akan mendapat konfirmasi setelah order disetujui.
                        </div>

                        <button type="submit"
                            class="w-full rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600 transition">
                            Ajukan Order Sekarang
                        </button>

                    </form>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection