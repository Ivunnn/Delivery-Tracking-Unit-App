@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Riwayat Pengiriman</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar pengiriman yang sudah selesai</p>
    </div>

    {{-- Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('driver.pengiriman.riwayat') }}"
            class="flex gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode, customer, tujuan..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('driver.pengiriman.riwayat') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- List --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Unit</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Customer</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Tujuan</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-400">Tgl Kirim</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($pengiriman as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $pengiriman->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-medium text-gray-800 dark:text-white/90">
                                {{ $item->kode_pengiriman }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">{{ $item->order->unit->tipe_motor }}</div>
                                <div class="text-xs text-gray-400">{{ $item->order->unit->warna }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->order->customer->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                {{ $item->tujuan }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->tanggal_kirim->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('driver.pengiriman.show', $item) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada riwayat pengiriman</p>
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