<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengiriman::with([
            'order.customer',
            'order.unit',
            'driver.user',
        ])->whereIn('status', ['berangkat', 'dalam_perjalanan', 'tiba'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search — nama driver, kode pengiriman, nama customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengiriman', 'like', "%{$search}%")
                    ->orWhereHas('driver.user', fn($q) =>
                        $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('order.customer', fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('nama_toko', 'like', "%{$search}%"));
            });
        }

        $pengiriman = $query->get();

        // Hapus $drivers dari sini karena filter driver sudah diganti search
        return view('pages.admin.monitoring.index', [
            'title' => 'Monitoring Tracking',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── Show — Detail Peta ───────────────────────────────────
    public function show(Pengiriman $pengiriman)
    {
        $pengiriman->load([
            'order.customer',
            'order.unit',
            'driver.user',
            'trackings',
            'buktiPengiriman',
        ]);

        return view('pages.admin.monitoring.show', [
            'title' => 'Detail Tracking',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── API Data — Auto Refresh ──────────────────────────────
    public function data(Pengiriman $pengiriman)
    {
        $pengiriman->load(['trackings']);

        $trackings = $pengiriman->trackings->map(fn($t) => [
            'status_tracking' => $t->status_tracking,
            'lokasi' => $t->lokasi,
            'lat' => $t->lat,
            'lng' => $t->lng,
            'catatan' => $t->catatan,
            'jam_update' => $t->jam_update?->format('d M Y, H:i'),
        ]);

        $terakhir = $pengiriman->trackings->first();

        return response()->json([
            'status' => $pengiriman->status,
            'status_label' => $pengiriman->status_badge['label'],
            'trackings' => $trackings,
            'posisi_terakhir' => $terakhir ? [
                'lat' => $terakhir->lat,
                'lng' => $terakhir->lng,
                'lokasi' => $terakhir->lokasi,
                'status_tracking' => $terakhir->status_tracking,
                'jam_update' => $terakhir->jam_update?->format('d M Y, H:i'),
            ] : null,
            'koordinat' => $trackings
                ->filter(fn($t) => $t['lat'] && $t['lng'])
                ->values()
                ->map(fn($t) => [$t['lat'], $t['lng']]),
        ]);
    }
}