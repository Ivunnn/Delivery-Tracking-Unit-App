@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Katalog Unit</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Unit sepeda motor yang tersedia untuk dipesan
        </p>
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
        <form method="GET" action="{{ route('customer.katalog.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari tipe motor, warna..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            @if ($tahuns->isNotEmpty())
                <select name="tahun"
                    class="rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahuns as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            @endif
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Cari
            </button>
            @if(request('search') || request('tahun'))
                <a href="{{ route('customer.katalog.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Grid Unit --}}
    @if ($units->isEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada unit yang tersedia saat ini</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($units as $unit)
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden hover:border-brand-300 dark:hover:border-brand-700 transition group">

                    {{-- Placeholder Gambar --}}
                    <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>

                    <div class="p-5 space-y-3">
                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-white/90 group-hover:text-brand-500 transition">
                                {{ $unit->tipe_motor }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $unit->warna }}
                                @if ($unit->tahun)
                                    · {{ $unit->tahun }}
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-lg font-bold text-gray-800 dark:text-white/90">
                                {{ $unit->harga ? $unit->harga_format : 'Hubungi Admin' }}
                            </p>
                            <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                Tersedia
                            </span>
                        </div>

                        <p class="text-xs font-mono text-gray-400">{{ $unit->no_rangka }}</p>

                        <a href="{{ route('customer.katalog.show', $unit) }}"
                            class="block w-full rounded-lg bg-brand-500 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-brand-600 transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($units->hasPages())
            <div>{{ $units->links() }}</div>
        @endif
    @endif

</div>
@endsection