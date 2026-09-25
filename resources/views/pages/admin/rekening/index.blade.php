@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Rekening Bank</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola rekening bank untuk pembayaran customer
            </p>
        </div>
        <a href="{{ route('admin.rekening.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rekening
        </a>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
            <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($rekening as $rek)
            <div class="rounded-2xl border bg-white p-5 dark:bg-gray-900
                {{ $rek->is_active
                    ? 'border-gray-200 dark:border-gray-800'
                    : 'border-gray-100 dark:border-gray-800 opacity-60' }}">

                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                            <span class="text-sm font-bold text-brand-600 dark:text-brand-400">
                                {{ strtoupper(substr($rek->nama_bank, 0, 3)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $rek->nama_bank }}
                            </p>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $rek->is_active
                                    ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'
                                    : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $rek->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <dl class="space-y-1.5 mb-4">
                    <div>
                        <dt class="text-xs text-gray-400">No. Rekening</dt>
                        <dd class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                            {{ $rek->no_rekening }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Atas Nama</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $rek->atas_nama }}</dd>
                    </div>
                </dl>

                <div class="flex gap-2">
                    <a href="{{ route('admin.rekening.edit', $rek) }}"
                        class="flex-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-center text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.rekening.destroy', $rek) }}"
                        onsubmit="return confirm('Hapus rekening {{ $rek->nama_bank }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="rounded-lg border border-error-200 px-3 py-1.5 text-xs font-medium text-error-600 hover:bg-error-50 transition dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/15">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-400">Belum ada rekening bank</p>
            </div>
        @endforelse
    </div>

</div>
@endsection