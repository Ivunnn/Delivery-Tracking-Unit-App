@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rekening.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Rekening</h2>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.rekening.update', $rekening) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>
                    <label for="nama_bank" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Bank <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nama_bank" name="nama_bank"
                        value="{{ old('nama_bank', $rekening->nama_bank) }}"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('nama_bank') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('nama_bank')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_rekening" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nomor Rekening <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="no_rekening" name="no_rekening"
                        value="{{ old('no_rekening', $rekening->no_rekening) }}"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm font-mono text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('no_rekening') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('no_rekening')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="atas_nama" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Atas Nama <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="atas_nama" name="atas_nama"
                        value="{{ old('atas_nama', $rekening->atas_nama) }}"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('atas_nama') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('atas_nama')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1"
                                {{ old('is_active', $rekening->is_active) == 1 ? 'checked' : '' }}
                                class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0"
                                {{ old('is_active', $rekening->is_active) == 0 ? 'checked' : '' }}
                                class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                <a href="{{ route('admin.rekening.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection