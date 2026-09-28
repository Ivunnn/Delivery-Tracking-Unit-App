@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.monitoring.index') }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Tracking</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">
                    {{ $pengiriman->kode_pengiriman }}
                </p>
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

        {{-- Progress Stepper --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            @php
                $steps = [
                    ['key' => 'menunggu', 'label' => 'Menunggu'],
                    ['key' => 'berangkat', 'label' => 'Berangkat'],
                    ['key' => 'dalam_perjalanan', 'label' => 'Dalam Perjalanan'],
                    ['key' => 'tiba', 'label' => 'Tiba'],
                    ['key' => 'selesai', 'label' => 'Selesai'],
                ];
                $stepKeys = array_column($steps, 'key');
                $activeStep = array_search($pengiriman->status, $stepKeys);
                $activeStep = $activeStep === false ? 0 : $activeStep;
            @endphp

            <div class="hidden sm:flex items-center relative">
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-100 dark:bg-gray-800 z-0"></div>
                <div class="absolute top-4 left-0 h-0.5 bg-brand-500 z-0 transition-all duration-500"
                    style="width: {{ $activeStep > 0 ? ($activeStep / (count($steps) - 1)) * 100 : 0 }}%">
                </div>
                @foreach ($steps as $i => $step)
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                                {{ $i < $activeStep ? 'bg-brand-500 text-white' : '' }}
                                {{ $i === $activeStep ? 'bg-brand-500 text-white ring-4 ring-brand-100 dark:ring-brand-900' : '' }}
                                {{ $i > $activeStep ? 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600' : '' }}">
                            @if ($i < $activeStep)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <p class="text-xs text-center mt-2 leading-tight font-medium
                                {{ $i <= $activeStep ? 'text-brand-500' : 'text-gray-400 dark:text-gray-600' }}">
                            {{ $step['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Mobile --}}
            <div class="flex sm:hidden items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-brand-500">{{ $badge['label'] }}</p>
                    <p class="text-xs text-gray-400">Langkah {{ $activeStep + 1 }} dari {{ count($steps) }}</p>
                </div>
            </div>

            @if ($pengiriman->estimasi_tiba && !in_array($pengiriman->status, ['tiba', 'selesai']))
                <div class="mt-4 flex items-center gap-2 rounded-lg bg-brand-50 dark:bg-brand-500/10 px-4 py-2.5">
                    <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm text-brand-700 dark:text-brand-400">
                        Estimasi tiba: <strong>{{ $pengiriman->estimasi_tiba->format('d M Y') }}</strong>
                    </p>
                </div>
            @endif
        </div>

        {{-- Peta --}}
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
                        Update terakhir: {{ $pengiriman->trackings->first()?->jam_update->format('H:i') }} WIB
                    </span>
                </div>
                <div id="map" style="height: 360px; z-index: 0;"></div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-3 text-gray-400">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <p class="text-sm">Driver belum mengupdate lokasi GPS.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Kolom Kiri: Timeline --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Riwayat Perjalanan</h3>
                        <span class="text-xs text-gray-400">Auto refresh tiap 30 detik</span>
                    </div>

                    <div id="timeline-container">
                        @if ($pengiriman->trackings->isEmpty())
                            <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada update dari driver.</p>
                        @else
                            <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-5 ml-3">
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
                                                {{ $track->jam_update->format('d M Y, H:i') }}
                                            </time>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-6">

                {{-- Info Driver --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Driver</h3>
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $pengiriman->driver->user->phone }}
                        </a>
                    @endif
                </div>

                {{-- Info Customer --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Customer</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs text-gray-400">Nama</dt>
                            <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $pengiriman->order->customer->name }}
                            </dd>
                        </div>
                        @if ($pengiriman->order->customer->nama_toko)
                            <div>
                                <dt class="text-xs text-gray-400">Nama Toko</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $pengiriman->order->customer->nama_toko }}
                                </dd>
                            </div>
                        @endif
                        @if ($pengiriman->order->customer->phone)
                            <div>
                                <dt class="text-xs text-gray-400">No. HP</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $pengiriman->order->customer->phone }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Info Pengiriman --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Detail Pengiriman</h3>
                    <dl class="space-y-2.5">
                        <div>
                            <dt class="text-xs text-gray-400">Unit</dt>
                            <dd class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $pengiriman->order->unit->tipe_motor }} · {{ $pengiriman->order->unit->warna }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400">No. Rangka</dt>
                            <dd class="text-sm font-mono text-gray-800 dark:text-white/90">
                                {{ $pengiriman->order->unit->no_rangka }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400">Tujuan</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">{{ $pengiriman->tujuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400">Tanggal Kirim</dt>
                            <dd class="text-sm text-gray-800 dark:text-white/90">
                                {{ $pengiriman->tanggal_kirim->format('d M Y') }}
                            </dd>
                        </div>
                        @if ($pengiriman->estimasi_tiba)
                            <div>
                                <dt class="text-xs text-gray-400">Estimasi Tiba</dt>
                                <dd class="text-sm text-gray-800 dark:text-white/90">
                                    {{ $pengiriman->estimasi_tiba->format('d M Y') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Link ke detail pengiriman --}}
                <a href="{{ route('admin.pengiriman.show', $pengiriman) }}"
                    class="flex items-center justify-center gap-2 w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Lihat Detail Pengiriman Lengkap
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

            </div>
        </div>

    </div>

    @if ($adaPeta)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endif
@endsection

@push('scripts')
    <script>
        const apiUrl = "{{ route('admin.monitoring.data', $pengiriman) }}";
        const adaPeta = {{ $adaPeta ? 'true' : 'false' }};
        const statusSelesai = {{ in_array($pengiriman->status, ['selesai']) ? 'true' : 'false' }};
        const driverName = @json($pengiriman->driver->user->name);

        let map, markerDriver, polyline;

        @if ($adaPeta)
            const leafletScript = document.createElement('script');
            leafletScript.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            leafletScript.onload = function () { initMap(); };
            document.head.appendChild(leafletScript);

            function initMap() {
                const koordinat = @json($koordinat->values());
                const terakhir = koordinat[koordinat.length - 1];

                map = L.map('map').setView(terakhir, 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                const ikonDriver = L.divIcon({
                    className: '',
                    html: `<div style="background:#3b82f6;width:14px;height:14px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3)"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7],
                });

                const driverPopup = document.createElement('strong');
                driverPopup.textContent = driverName;

                markerDriver = L.marker(terakhir, { icon: ikonDriver })
                    .addTo(map)
                    .bindPopup(driverPopup);

                if (koordinat.length > 1) {
                    polyline = L.polyline(koordinat, {
                        color: '#3b82f6', weight: 3, opacity: 0.7, dashArray: '6, 4',
                    }).addTo(map);
                }
            }
        @endif

            function buildTimeline(trackings) {
                if (trackings.length === 0) {
                    const emptyState = document.createElement('p');
                    emptyState.className = 'text-sm text-gray-400 dark:text-gray-600';
                    emptyState.textContent = 'Belum ada update dari driver.';
                    return emptyState;
                }

                const timeline = document.createElement('ol');
                timeline.className = 'relative border-l border-gray-200 dark:border-gray-700 space-y-5 ml-3';

                trackings.forEach(t => {
                    const item = document.createElement('li');
                    item.className = 'ml-6';

                    const marker = document.createElement('span');
                    marker.className = 'absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 ring-8 ring-white dark:ring-gray-900 dark:bg-brand-900';
                    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    icon.setAttribute('class', 'w-3 h-3 text-brand-500');
                    icon.setAttribute('fill', 'currentColor');
                    icon.setAttribute('viewBox', '0 0 20 20');
                    icon.setAttribute('aria-hidden', 'true');
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('fill-rule', 'evenodd');
                    path.setAttribute('d', 'M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z');
                    path.setAttribute('clip-rule', 'evenodd');
                    icon.appendChild(path);
                    marker.appendChild(icon);

                    const row = document.createElement('div');
                    row.className = 'flex items-start justify-between gap-4';
                    const content = document.createElement('div');

                    const status = document.createElement('p');
                    status.className = 'text-sm font-medium text-gray-800 dark:text-white/90';
                    status.textContent = t.status_tracking || '-';
                    content.appendChild(status);

                    if (t.lokasi) {
                        const location = document.createElement('p');
                        location.className = 'text-xs text-gray-500 mt-0.5';
                        location.textContent = `📍 ${t.lokasi}`;
                        content.appendChild(location);
                    }

                    if (t.catatan) {
                        const note = document.createElement('p');
                        note.className = 'text-xs text-gray-400 mt-0.5';
                        note.textContent = t.catatan;
                        content.appendChild(note);
                    }

                    const time = document.createElement('time');
                    time.className = 'text-xs text-gray-400 shrink-0';
                    time.textContent = t.jam_update || '-';

                    row.append(content, time);
                    item.append(marker, row);
                    timeline.appendChild(item);
                });

                return timeline;
            }

            async function refresh() {
                try {
                    const res = await fetch(apiUrl);
                    const data = await res.json();

                    document.getElementById('timeline-container').replaceChildren(buildTimeline(data.trackings));

                    if (map && data.koordinat.length > 0) {
                        const terakhir = data.koordinat[data.koordinat.length - 1];
                        markerDriver.setLatLng(terakhir);
                        map.panTo(terakhir);
                        if (polyline) {
                            polyline.setLatLngs(data.koordinat);
                        } else if (data.koordinat.length > 1) {
                            polyline = L.polyline(data.koordinat, {
                                color: '#3b82f6', weight: 3, opacity: 0.7, dashArray: '6, 4',
                            }).addTo(map);
                        }
                    }

                    if (data.posisi_terakhir) {
                        const label = document.getElementById('last-update-label');
                        if (label) label.textContent = `Update terakhir: ${data.posisi_terakhir.jam_update}`;
                    }
                } catch (e) {
                    console.warn('Refresh gagal:', e);
                }
            }

        if (!statusSelesai) {
            setInterval(refresh, 30000);
        }
    </script>
@endpush