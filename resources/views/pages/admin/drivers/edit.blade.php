@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.drivers.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Driver</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $driver->user->name }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.drivers.update', $driver) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Informasi Akun --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Lengkap <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $driver->user->name) }}"
                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                            {{ $errors->has('name') ? 'border-error-400' : 'border-gray-300' }}" />
                        @error('name') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor HP <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="phone" name="phone"
                            value="{{ old('phone', $driver->user->phone) }}"
                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                            {{ $errors->has('phone') ? 'border-error-400' : 'border-gray-300' }}" />
                        @error('phone') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email <span class="text-error-500">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $driver->user->email) }}"
                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                            {{ $errors->has('email') ? 'border-error-400' : 'border-gray-300' }}" />
                        @error('email') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Password Baru
                            <span class="text-xs font-normal text-gray-400">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 8 karakter"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('password') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status Akun
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="1"
                                    {{ old('is_active', $driver->user->is_active) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="0"
                                    {{ old('is_active', $driver->user->is_active) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800"></div>

            {{-- Informasi Driver --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Driver</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="no_ktp" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor KTP
                        </label>
                        <input type="text" id="no_ktp" name="no_ktp"
                            value="{{ old('no_ktp', $driver->no_ktp) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm font-mono text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                            {{ $errors->has('no_ktp') ? 'border-error-400' : 'border-gray-300' }}" />
                        @error('no_ktp') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="no_sim" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor SIM
                        </label>
                        <input type="text" id="no_sim" name="no_sim"
                            value="{{ old('no_sim', $driver->no_sim) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status Driver <span class="text-error-500">*</span>
                        </label>
                        <select id="status" name="status"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="tersedia" {{ old('status', $driver->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="bertugas" {{ old('status', $driver->status) === 'bertugas' ? 'selected' : '' }}>Bertugas</option>
                        </select>
                        @error('status') <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                <a href="{{ route('admin.drivers.index') }}"
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