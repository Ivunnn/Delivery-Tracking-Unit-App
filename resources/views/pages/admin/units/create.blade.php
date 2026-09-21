@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.units.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Tambah Unit</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambah data unit sepeda motor baru</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.units.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- No Rangka --}}
                <div class="sm:col-span-2">
                    <label for="no_rangka" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nomor Rangka <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="no_rangka" name="no_rangka"
                        value="{{ old('no_rangka') }}"
                        placeholder="Contoh: MH1JFP110PK000001"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 font-mono
                        {{ $errors->has('no_rangka') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('no_rangka')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Motor --}}
                <div>
                    <label for="tipe_motor" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tipe Motor <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="tipe_motor" name="tipe_motor"
                        value="{{ old('tipe_motor') }}"
                        placeholder="Contoh: Honda Beat ESP"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                        {{ $errors->has('tipe_motor') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('tipe_motor')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Warna --}}
                <div>
                    <label for="warna" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Warna <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="warna" name="warna"
                        value="{{ old('warna') }}"
                        placeholder="Contoh: Hitam"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                        {{ $errors->has('warna') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('warna')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun --}}
                <div>
                    <label for="tahun" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tahun
                    </label>
                    <input type="number" id="tahun" name="tahun"
                        value="{{ old('tahun', date('Y')) }}"
                        min="2000" max="{{ date('Y') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    @error('tahun')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label for="harga" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Harga (Rp)
                    </label>
                    <input type="number" id="harga" name="harga"
                        value="{{ old('harga') }}"
                        placeholder="Contoh: 17500000"
                        min="0"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('harga')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="sm:col-span-2">
                    <label for="keterangan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Keterangan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        placeholder="Keterangan tambahan (opsional)"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                <a href="{{ route('admin.units.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    Simpan Unit
                </button>
            </div>

        </form>
    </div>

</div>
@endsection