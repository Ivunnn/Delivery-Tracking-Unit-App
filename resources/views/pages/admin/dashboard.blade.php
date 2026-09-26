@extends('layouts.app')

@section('content')
  <div class="space-y-6">

      {{-- Header --}}
      <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Dashboard</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              Selamat datang, {{ Auth::user()->name }} · {{ now()->translatedFormat('l, d F Y') }}
          </p>
      </div>

      {{-- Widget Statistik Utama --}}
      <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">

          {{-- Customer --}}
          <a href="{{ route('admin.customers.index') }}"
              class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 transition dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700">
              <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                  </div>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Customers</p>
              <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalCustomer }}</p>
          </a>

          {{-- Orders --}}
          <a href="{{ route('admin.orders.index') }}"
              class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 transition dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700">
              <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                      </svg>
                  </div>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Orders</p>
              <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalOrder }}</p>
          </a>

          {{-- Units --}}
          <a href="{{ route('admin.units.index') }}"
              class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 transition dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700">
              <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                      </svg>
                  </div>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Units</p>
              <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalUnit }}</p>
          </a>

          {{-- Drivers --}}
          <a href="{{ route('admin.drivers.index') }}"
              class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 transition dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700">
              <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                  </div>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Drivers</p>
              <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $totalDriver }}</p>
          </a>

          {{-- Paid Revenue --}}
          <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 lg:col-span-1 col-span-2">
              <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                  </div>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Paid Revenue</p>
              <p class="text-2xl font-bold text-success-500 mt-1 leading-tight">
                  Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
              </p>
          </div>

      </div>

      {{-- Widget Sekunder --}}
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div class="rounded-2xl border border-warning-200 bg-warning-50 p-5 dark:border-warning-500/30 dark:bg-warning-500/10">
              <p class="text-xs font-medium text-warning-600 dark:text-warning-400">Order Menunggu</p>
              <p class="text-3xl font-bold text-warning-700 dark:text-warning-300 mt-1">{{ $orderMenunggu }}</p>
              <p class="text-xs text-warning-500 mt-1">Perlu disetujui</p>
          </div>
          <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-500/30 dark:bg-blue-500/10">
              <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Pengiriman Aktif</p>
              <p class="text-3xl font-bold text-blue-700 dark:text-blue-300 mt-1">{{ $pengirimanAktif }}</p>
              <p class="text-xs text-blue-500 mt-1">Sedang berjalan</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
              <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Bukti Transfer</p>
              <p class="text-3xl font-bold text-gray-800 dark:text-white/90 mt-1">{{ $buktiMenunggu }}</p>
              <p class="text-xs text-gray-400 mt-1">Menunggu verifikasi</p>
          </div>
      </div>

      {{-- Dua Kolom Bawah --}}
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          {{-- Recent Orders --}}
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                  <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Recent Orders</h3>
                  <a href="{{ route('admin.orders.index') }}"
                      class="text-xs text-brand-500 hover:text-brand-600">See all</a>
              </div>

              @forelse ($recentOrders as $order)
                @php
                  $badge = $order->status_badge;
                  $colors = [
                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                    'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                    'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                  ];
                @endphp
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">

                    {{-- Foto Unit --}}
                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700 shrink-0 bg-gray-50 dark:bg-gray-800">
                        @if ($order->unit->foto)
                          <img src="{{ $order->unit->foto_url }}"
                              alt="{{ $order->unit->tipe_motor }}"
                              class="w-full h-full object-cover" />
                        @else
                          <div class="w-full h-full flex items-center justify-center">
                              <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                              </svg>
                          </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
                            {{ $order->unit->tipe_motor }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $order->customer->name }}
                            @if ($order->customer->nama_toko)
                              · {{ $order->customer->nama_toko }}
                            @endif
                        </p>
                    </div>

                    {{-- Harga --}}
                    <div class="text-right shrink-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $order->unit->harga ? $order->unit->harga_format : '-' }}
                        </p>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $colors[$badge['color']] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                </a>
              @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400 dark:text-gray-600">Belum ada order</p>
                </div>
              @endforelse
          </div>

          {{-- Pengiriman Aktif --}}
          <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                  <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengiriman Aktif</h3>
                  <a href="{{ route('admin.pengiriman.index') }}"
                      class="text-xs text-brand-500 hover:text-brand-600">See all</a>
              </div>

              @forelse ($pengirimanAktifList as $item)
                @php
                  $badge = $item->status_badge;
                  $colors = [
                    'default' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                    'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                  ];
                @endphp
                <a href="{{ route('admin.pengiriman.show', $item) }}"
                    class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">

                    {{-- Icon --}}
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-mono font-medium text-gray-800 dark:text-white/90">
                            {{ $item->kode_pengiriman }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $item->driver->user->name }}
                            · {{ $item->order->unit->tipe_motor }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            📍 {{ $item->tujuan }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="shrink-0">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $colors[$badge['color']] ?? $colors['default'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                </a>
              @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400 dark:text-gray-600">Tidak ada pengiriman aktif</p>
                </div>
              @endforelse
          </div>

      </div>

  </div>
@endsection