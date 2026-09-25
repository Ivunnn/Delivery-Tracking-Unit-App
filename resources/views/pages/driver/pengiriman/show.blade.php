@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('driver.pengiriman.aktif') }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Pengiriman</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $pengiriman->kode_pengiriman }}</p>
            </div>
            @php
                $badge = $pengiriman->status_badge;
                $colors = [
                    'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                    'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                ];
            @endphp
            <span
                class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                {{ $badge['label'] }}
            </span>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-500/30 dark:bg-success-500/15">
                <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Update Status + Lokasi --}}
        @if ($pengiriman->status !== 'selesai')
            <div x-data="{
                    status: '{{ $pengiriman->status }}',
                    lat: null,
                    lng: null,
                    loadingGps: false,
                    gpsError: '',
                    getLokasi() {
                        this.loadingGps = true
                        this.gpsError = ''
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                this.lat = pos.coords.latitude
                                this.lng = pos.coords.longitude
                                this.loadingGps = false
                            },
                            (err) => {
                                this.gpsError = 'GPS tidak tersedia. Isi lokasi manual.'
                                this.loadingGps = false
                            },
                            { enableHighAccuracy: true, timeout: 10000 }
                        )
                    }
                }" class="rounded-2xl border border-brand-200 bg-brand-50 p-6 dark:border-brand-500/30 dark:bg-brand-500/10">

                <h3 class="text-sm font-semibold text-brand-700 dark:text-brand-400 mb-4">
                    Update Status Pengiriman
                </h3>

                <form method="POST" action="{{ route('driver.pengiriman.update-status', $pengiriman) }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="lat" :value="lat">
                    <input type="hidden" name="lng" :value="lng">

                    {{-- Status --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status Baru <span class="text-error-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            @php
                                $statusOptions = [
                                    'berangkat' => 'Berangkat',
                                    'dalam_perjalanan' => 'Dalam Perjalanan',
                                    'tiba' => 'Tiba di Lokasi',
                                    'selesai' => 'Selesai',
                                ];

                                // Status yang hanya boleh dipilih 1x
                                $sekaliSaja = ['berangkat', 'tiba', 'selesai'];

                                // Status yang sudah pernah dipakai
                                $sudahDipakai = $pengiriman->trackings
                                    ->pluck('status_tracking')
                                    ->map(fn($s) => match ($s) {
                                        'Berangkat dari gudang' => 'berangkat',
                                        'Tiba di Lokasi' => 'tiba',
                                        'Pengiriman Selesai' => 'selesai',
                                        default => 'dalam_perjalanan',
                                    })->unique()->values()->toArray();

                                $statusSekarang = $pengiriman->status;
                            @endphp

                            @foreach ($statusOptions as $val => $label)
                                        @php
                                            // Logika disabled per status:
                                            // dalam_perjalanan → selalu enabled kecuali sudah selesai/tiba
                                            // sekali saja      → disabled kalau sudah pernah dipakai
                                            // status sebelum current → disabled

                                            $isSelesaiAtauTiba = in_array($statusSekarang, ['tiba', 'selesai']);

                                            if ($val === 'dalam_perjalanan') {
                                                $disabled = $isSelesaiAtauTiba;
                                            } elseif (in_array($val, $sekaliSaja)) {
                                                $disabled = in_array($val, $sudahDipakai)
                                                    || ($val === 'berangkat' && $statusSekarang !== 'menunggu')
                                                    || ($val === 'tiba' && !in_array($statusSekarang, ['berangkat', 'dalam_perjalanan']))
                                                    || ($val === 'selesai' && $statusSekarang !== 'tiba');
                                            } else {
                                                $disabled = false;
                                            }
                                        @endphp

                                        <label
                                            class="flex items-center gap-2 rounded-lg border p-3 transition
                                    {{ $disabled
                                ? 'opacity-40 cursor-not-allowed border-gray-200 dark:border-gray-700'
                                : 'cursor-pointer hover:border-brand-300 dark:hover:border-brand-700 border-gray-200 dark:border-gray-700' }}">
                                            <input type="radio" name="status" value="{{ $val }}" {{ $disabled ? 'disabled' : '' }}
                                                class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500" />
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- GPS --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Lokasi GPS
                        </label>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="getLokasi()"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="loadingGps ? 'Mengambil GPS...' : 'Ambil Lokasi GPS'"></span>
                            </button>
                            <span x-show="lat" class="text-xs text-success-500"
                                x-text="`✓ ${lat?.toFixed(5)}, ${lng?.toFixed(5)}`"></span>
                            <span x-show="gpsError" class="text-xs text-warning-500" x-text="gpsError"></span>
                        </div>
                    </div>

                    {{-- Nama Lokasi Manual --}}
                    <div>
                        <label for="lokasi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Lokasi <span class="text-xs font-normal text-gray-400">(opsional, untuk deskripsi)</span>
                        </label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}"
                            placeholder="Contoh: Memasuki Kota Lamongan"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="catatan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" id="catatan" name="catatan" value="{{ old('catatan') }}"
                            placeholder="Catatan tambahan..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600 transition">
                        Update Status
                    </button>

                </form>
            </div>
        @endif

        {{-- Upload Bukti --}}
        @if (in_array($pengiriman->status, ['tiba', 'selesai']))
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                    {{ $pengiriman->buktiPengiriman ? 'Ganti Bukti Pengiriman' : 'Upload Bukti Pengiriman' }}
                </h3>

                @if ($pengiriman->buktiPengiriman)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $pengiriman->buktiPengiriman->foto_bukti) }}" alt="Bukti Pengiriman"
                            class="w-full max-w-xs rounded-lg border border-gray-200 dark:border-gray-700 mb-2" />
                        <p class="text-xs text-gray-400">
                            Diupload: {{ $pengiriman->buktiPengiriman->waktu_upload->format('d M Y, H:i') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('driver.pengiriman.upload-bukti', $pengiriman) }}"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="foto_bukti" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Foto Tanda Terima <span class="text-error-500">*</span>
                        </label>
                        <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpg,image/jpeg,image/png" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 file:mr-4 file:rounded file:border-0 file:bg-brand-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-brand-600 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                                {{ $errors->has('foto_bukti') ? 'border-error-400' : '' }}" />
                        @error('foto_bukti')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                    </div>

                    <div>
                        <label for="keterangan_bukti" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Keterangan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" id="keterangan_bukti" name="keterangan"
                            value="{{ old('keterangan', $pengiriman->buktiPengiriman?->keterangan) }}"
                            placeholder="Keterangan bukti pengiriman..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-success-500 px-4 py-3 text-sm font-medium text-white hover:bg-success-600 transition">
                        {{ $pengiriman->buktiPengiriman ? 'Ganti Foto Bukti' : 'Upload Bukti Pengiriman' }}
                    </button>

                </form>
            </div>
        @endif

        {{-- Info Pengiriman --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Detail Pengiriman</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tujuan</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->tujuan }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tanggal Kirim</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">
                            {{ $pengiriman->tanggal_kirim->format('d M Y') }}</dd>
                    </div>
                    @if ($pengiriman->estimasi_tiba)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Estimasi Tiba</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                {{ $pengiriman->estimasi_tiba->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Unit & Customer</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tipe Motor</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $pengiriman->order->unit->tipe_motor }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">No. Rangka</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">
                            {{ $pengiriman->order->unit->no_rangka }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Warna</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->order->unit->warna }}</dd>
                    </div>
                    <div class="border-t border-gray-100 pt-3 dark:border-gray-800">
                        <dt class="text-xs text-gray-400 mb-0.5">Customer</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $pengiriman->order->customer->name }}</dd>
                        @if ($pengiriman->order->customer->phone)
                            <dd class="text-xs text-gray-400 mt-0.5">{{ $pengiriman->order->customer->phone }}</dd>
                        @endif
                    </div>
                </dl>
            </div>

        </div>

        {{-- Timeline Tracking --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Riwayat Update</h3>

            @if ($pengiriman->trackings->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada update. Mulai dengan pilih status di atas.</p>
            @else
                <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-6 ml-3">
                    @foreach ($pengiriman->trackings as $track)
                        <li class="ml-6">
                            <span
                                class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 ring-8 ring-white dark:ring-gray-900 dark:bg-brand-900">
                                <svg class="w-3 h-3 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $track->status_tracking }}
                                    </p>
                                    @if ($track->lokasi)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            📍 {{ $track->lokasi }}
                                        </p>
                                    @endif
                                    @if ($track->lat && $track->lng)
                                        <p class="text-xs text-gray-400 mt-0.5 font-mono">
                                            {{ number_format($track->lat, 5) }}, {{ number_format($track->lng, 5) }}
                                        </p>
                                    @endif
                                    @if ($track->catatan)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $track->catatan }}</p>
                                    @endif
                                </div>
                                <time class="text-xs text-gray-400 shrink-0 ml-4">
                                    {{ $track->jam_update->format('d M Y, H:i') }}
                                </time>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

    </div>
@endsection