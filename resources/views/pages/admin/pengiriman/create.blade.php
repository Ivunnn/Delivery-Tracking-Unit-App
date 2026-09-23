@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.pengiriman.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Buat Pengiriman</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assign driver dan buat data pengiriman</p>
        </div>
    </div>

    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/15">
            <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.pengiriman.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Pilih Order --}}
                <div class="sm:col-span-2">
                    <label for="id_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Order <span class="text-error-500">*</span>
                    </label>
                    <select id="id_order" name="id_order"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('id_order') ? 'border-error-400' : 'border-gray-300' }}">
                        <option value="">-- Pilih Order --</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}"
                                {{ old('id_order', $selectedOrder?->id) == $order->id ? 'selected' : '' }}>
                                {{ $order->kode_order }} —
                                {{ $order->customer->name }}
                                @if($order->customer->nama_toko)
                                    ({{ $order->customer->nama_toko }})
                                @endif
                                — {{ $order->unit->tipe_motor }} {{ $order->unit->warna }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_order')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                    @if ($orders->isEmpty())
                        <p class="mt-1.5 text-xs text-warning-500">
                            Tidak ada order yang siap dikirim. Pastikan ada order berstatus "Disetujui".
                        </p>
                    @endif
                </div>

                {{-- Pilih Driver --}}
                <div class="sm:col-span-2">
                    <label for="id_driver" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Driver <span class="text-error-500">*</span>
                    </label>
                    <select id="id_driver" name="id_driver"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('id_driver') ? 'border-error-400' : 'border-gray-300' }}">
                        <option value="">-- Pilih Driver --</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}"
                                {{ old('id_driver') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->user->name }} — {{ $driver->user->phone ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_driver')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                    @if ($drivers->isEmpty())
                        <p class="mt-1.5 text-xs text-warning-500">
                            Tidak ada driver yang tersedia saat ini.
                        </p>
                    @endif
                </div>

                {{-- Tanggal Kirim --}}
                <div>
                    <label for="tanggal_kirim" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tanggal Kirim <span class="text-error-500">*</span>
                    </label>
                    <input type="date" id="tanggal_kirim" name="tanggal_kirim"
                        value="{{ old('tanggal_kirim', date('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                        {{ $errors->has('tanggal_kirim') ? 'border-error-400' : 'border-gray-300' }}" />
                    @error('tanggal_kirim')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estimasi Tiba --}}
                <div>
                    <label for="estimasi_tiba" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Estimasi Tiba
                        <span class="text-xs font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input type="date" id="estimasi_tiba" name="estimasi_tiba"
                        value="{{ old('estimasi_tiba') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    @error('estimasi_tiba')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tujuan --}}
                <div class="sm:col-span-2">
                    <label for="tujuan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Alamat Tujuan Pengiriman <span class="text-error-500">*</span>
                    </label>
                    <textarea id="tujuan" name="tujuan" rows="3"
                        placeholder="Tulis alamat tujuan pengiriman lengkap..."
                        class="w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                        {{ $errors->has('tujuan') ? 'border-error-400' : 'border-gray-300' }}">{{ old('tujuan', $selectedOrder?->customer?->alamat) }}</textarea>
                    @error('tujuan')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                <a href="{{ route('admin.pengiriman.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    Buat Pengiriman
                </button>
            </div>

        </form>
    </div>

</div>
@endsection