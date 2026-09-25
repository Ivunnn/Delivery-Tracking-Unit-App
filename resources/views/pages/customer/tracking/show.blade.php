@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.orders.show', $pengiriman->order) }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Tracking Pengiriman</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">
                {{ $pengiriman->kode_pengiriman }}
            </p>
        </div>
    </div>

    {{-- Progress Stepper --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        @php
            $steps = [
                ['key' => 'menunggu',         'label' => 'Menunggu'],
                ['key' => 'berangkat',        'label' => 'Berangkat'],
                ['key' => 'dalam_perjalanan', 'label' => 'Dalam Perjalanan'],
                ['key' => 'tiba',             'label' => 'Tiba'],
                ['key' => 'selesai',          'label' => 'Selesai'],
            ];
            $stepKeys   = array_column($steps, 'key');
            $activeStep = array_search($pengiriman->status, $stepKeys);
            $activeStep = $activeStep === false ? 0 : $activeStep;
        @endphp

        {{-- Desktop Stepper --}}
        <div class="hidden sm:flex items-center relative" id="stepper-container">
            {{-- Garis background --}}
            <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-100 dark:bg-gray-800 z-0"></div>
            {{-- Garis progress --}}
            <div class="absolute top-4 left-0 h-0.5 bg-brand-500 z-0 transition-all duration-500"
                style="width: {{ $activeStep > 0 ? ($activeStep / (count($steps) - 1)) * 100 : 0 }}%">
            </div>

            @foreach ($steps as $i => $step)
                <div class="flex flex-col items-center z-10 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $i < $activeStep  ? 'bg-brand-500 text-white' : '' }}
                        {{ $i === $activeStep ? 'bg-brand-500 text-white ring-4 ring-brand-100 dark:ring-brand-900' : '' }}
                        {{ $i > $activeStep  ? 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600' : '' }}">
                        @if ($i < $activeStep)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <p class="text-xs text-center mt-2 leading-tight font-medium
                        {{ $i <= $activeStep
                            ? 'text-brand-500'
                            : 'text-gray-400 dark:text-gray-600' }}">
                        {{ $step['label'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Mobile Stepper --}}
        <div class="flex sm:hidden items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-brand-500" id="mobile-status-label">
                    {{ $pengiriman->status_badge['label'] }}
                </p>
                <p class="text-xs text-gray-400">
                    Langkah {{ $activeStep + 1 }} dari {{ count($steps) }}
                </p>
            </div>
        </div>

        {{-- Estimasi Tiba --}}
        @if ($pengiriman->estimasi_tiba && !in_array($pengiriman->status, ['tiba','selesai']))
            <div class="mt-4 flex items-center gap-2 rounded-lg bg-brand-50 dark:bg-brand-500/10 px-4 py-2.5">
                <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-brand-700 dark:text-brand-400">
                    Estimasi tiba: <strong>{{ $pengiriman->estimasi_tiba->format('d M Y') }}</strong>
                </p>
            </div>
        @endif
    </div>

    {{-- Peta Leaflet --}}
    @php
        $koordinat = $pengiriman->trackings
            ->filter(fn($t) => $t->lat && $t->lng)
            ->map(fn($t) => [$t->lat, $t->lng])
            ->values();
        $adaPeta = $koordinat->isNotEmpty();
    @endphp

    @if ($adaPeta)
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-gray-900">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Posisi Driver</h3>
                <span class="text-xs text-gray-400" id="last-update-label">
                    Update terakhir: {{ $pengiriman->trackings->first()?->jam_update?->format('H:i') ?? '-' }} WIB
                </span>
            </div>
            <div id="map" style="height: 320px; z-index: 0;"></div>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3 text-gray-400">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <p class="text-sm">Posisi driver belum tersedia. Peta akan muncul setelah driver mengupdate lokasi GPS.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri: Timeline --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Riwayat Perjalanan</h3>
                    <span class="text-xs text-gray-400" id="refresh-label">
                        Auto refresh tiap 30 detik
                    </span>
                </div>

                <div id="timeline-container">
                    @if ($pengiriman->trackings->isEmpty())
                        <p class="text-sm text-gray-400 dark:text-gray-600" id="no-tracking-msg">
                            Belum ada update dari driver.
                        </p>
                    @else
                        <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-5 ml-3">
                            @foreach ($pengiriman->trackings as $track)
                                <li class="ml-6">
                                    <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 ring-8 ring-white dark:ring-gray-900 dark:bg-brand-900">
                                        <svg class="w-3 h-3 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $track->status_tracking }}
                                            </p>
                                            @if ($track->lokasi)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    📍 {{ $track->lokasi }}
                                                </p>
                                            @endif
                                            @if ($track->catatan)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $track->catatan }}</p>
                                            @endif
                                        </div>
                                        <time class="text-xs text-gray-400 shrink-0">
                                            {{ $track->jam_update?->format('d M Y, H:i') ?? '-' }}
                                        </time>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info --}}
        <div class="space-y-6">

            {{-- Info Driver --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Driver</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $pengiriman->driver->user->name }}
                        </p>
                        <p class="text-xs text-gray-400">Driver Pengiriman</p>
                    </div>
                </div>
                @if ($pengiriman->driver->user->phone)
                    <a href="tel:{{ $pengiriman->driver->user->phone }}"
                        class="flex items-center gap-2 w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $pengiriman->driver->user->phone }}
                    </a>
                @endif
            </div>

            {{-- Info Pengiriman --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Detail Pengiriman</h3>
                <dl class="space-y-2.5">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Kode Pengiriman</dt>
                        <dd class="text-sm font-mono text-gray-800 dark:text-white/90">{{ $pengiriman->kode_pengiriman }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Unit</dt>
                        <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $pengiriman->order->unit->tipe_motor }} · {{ $pengiriman->order->unit->warna }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tujuan</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->tujuan }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Tanggal Kirim</dt>
                        <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->tanggal_kirim?->format('d M Y') ?? 'Belum ditentukan' }}</dd>
                    </div>
                    @if ($pengiriman->estimasi_tiba)
                        <div>
                            <dt class="text-xs text-gray-400 mb-0.5">Estimasi Tiba</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->estimasi_tiba->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Bukti Pengiriman --}}
            @if ($pengiriman->buktiPengiriman)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Bukti Pengiriman</h3>
                    <img src="{{ asset('storage/' . $pengiriman->buktiPengiriman->foto_bukti) }}"
                        alt="Bukti Pengiriman"
                        class="w-full rounded-lg border border-gray-200 dark:border-gray-700 mb-2" />
                    <p class="text-xs text-gray-400">
                        {{ $pengiriman->buktiPengiriman->waktu_upload->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>

{{-- Leaflet CSS --}}
@if ($adaPeta)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endif
@endsection

@push('scripts')
<script>
    const pengirimanId  = {{ $pengiriman->id }};
    const apiUrl        = "{{ route('customer.tracking.data', $pengiriman) }}";
    const adaPeta       = {{ $adaPeta ? 'true' : 'false' }};
    const statusSelesai = {{ in_array($pengiriman->status, ['selesai']) ? 'true' : 'false' }};

    // ── Peta Leaflet ─────────────────────────────────────────
    let map, markerDriver, polyline;

    @if ($adaPeta)
    // Load Leaflet JS
    const leafletScript  = document.createElement('script');
    leafletScript.src    = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    leafletScript.onload = function () { initMap(); };
    document.head.appendChild(leafletScript);

    function initMap() {
        const koordinat = @json($koordinat->values());
        const terakhir  = koordinat[koordinat.length - 1];

        map = L.map('map').setView(terakhir, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marker driver
        const ikonDriver = L.divIcon({
            className: '',
            html: `<div style="
                background:#3b82f6;width:14px;height:14px;
                border-radius:50%;border:3px solid white;
                box-shadow:0 2px 6px rgba(0,0,0,0.3)">
            </div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7],
        });

        markerDriver = L.marker(terakhir, { icon: ikonDriver })
            .addTo(map)
            .bindPopup(`<b>Posisi Driver</b><br>{{ $pengiriman->driver->user->name }}`);

        // Breadcrumb trail
        if (koordinat.length > 1) {
            polyline = L.polyline(koordinat, {
                color: '#3b82f6',
                weight: 3,
                opacity: 0.7,
                dashArray: '6, 4',
            }).addTo(map);
        }
    }
    @endif

    // ── Auto Refresh ─────────────────────────────────────────
    function buildTimeline(trackings) {
        if (trackings.length === 0) {
            return '<p class="text-sm text-gray-400 dark:text-gray-600">Belum ada update dari driver.</p>';
        }

        let html = '<ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-5 ml-3">';
        trackings.forEach(t => {
            html += `
                <li class="ml-6">
                    <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 ring-8 ring-white dark:ring-gray-900 dark:bg-brand-900">
                        <svg class="w-3 h-3 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">${t.status_tracking}</p>
                            ${t.lokasi ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">📍 ${t.lokasi}</p>` : ''}
                            ${t.catatan ? `<p class="text-xs text-gray-400 mt-0.5">${t.catatan}</p>` : ''}
                        </div>
                        <time class="text-xs text-gray-400 shrink-0">${t.jam_update}</time>
                    </div>
                </li>`;
        });
        html += '</ol>';
        return html;
    }

    function updateStepper(status) {
        const stepKeys = ['menunggu','berangkat','dalam_perjalanan','tiba','selesai'];
        const stepLabels = ['Menunggu','Berangkat','Dalam Perjalanan','Tiba','Selesai'];
        const activeStep = stepKeys.indexOf(status);

        // Update mobile label
        const mobileLabel = document.getElementById('mobile-status-label');
        if (mobileLabel && activeStep >= 0) {
            mobileLabel.textContent = stepLabels[activeStep];
        }
    }

    async function refresh() {
        try {
            const res  = await fetch(apiUrl);
            const data = await res.json();

            // Update timeline
            document.getElementById('timeline-container').innerHTML = buildTimeline(data.trackings);

            // Update stepper
            updateStepper(data.status);

            // Update peta kalau ada koordinat baru
            if (map && data.koordinat.length > 0) {
                const terakhir = data.koordinat[data.koordinat.length - 1];
                markerDriver.setLatLng(terakhir);
                map.panTo(terakhir);

                if (polyline) {
                    polyline.setLatLngs(data.koordinat);
                } else if (data.koordinat.length > 1) {
                    polyline = L.polyline(data.koordinat, {
                        color: '#3b82f6',
                        weight: 3,
                        opacity: 0.7,
                        dashArray: '6, 4',
                    }).addTo(map);
                }
            }

            // Update last update label
            if (data.posisi_terakhir) {
                const label = document.getElementById('last-update-label');
                if (label) label.textContent = `Update terakhir: ${data.posisi_terakhir.jam_update}`;
            }

        } catch (e) {
            console.warn('Refresh gagal:', e);
        }
    }

    // Jangan polling kalau sudah selesai
    if (!statusSelesai) {
        setInterval(refresh, 30000);
    }
</script>
@endpush