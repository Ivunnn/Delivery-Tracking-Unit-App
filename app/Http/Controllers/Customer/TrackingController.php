<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    // ── Index — Daftar Pengiriman Customer ───────────────────────
    public function index()
    {
        $pengiriman = Pengiriman::query()
            ->select([
                'id',
                'id_order',
                'id_driver',
                'kode_pengiriman',
                'tanggal_kirim',
                'estimasi_tiba',
                'tujuan',
                'status',
                'created_at',
            ])
            ->whereHas('order', fn($q) => $q->where('id_customer', Auth::id()))
            ->with([
                'order:id,id_customer,id_unit',
                'order.unit:id,tipe_motor,warna',
                'driver:id,id_user',
                'driver.user:id,name',
                'latestTracking',
            ])
            ->latest()
            ->paginate(10);

        return view('pages.customer.tracking.index', [
            'title' => 'Tracking Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── Show Halaman Tracking ────────────────────────────────
    public function show(Pengiriman $pengiriman)
    {
        // Pastikan pengiriman milik customer yang login
        abort_if($pengiriman->order->id_customer !== Auth::id(), 403);

        $pengiriman->load([
            'order.unit',
            'driver.user',
            'trackings',
            'buktiPengiriman',
        ]);

        return view('pages.customer.tracking.show', [
            'title' => 'Tracking Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── API Endpoint — Auto Refresh ──────────────────────────
    public function data(Pengiriman $pengiriman)
    {
        abort_if($pengiriman->order->id_customer !== Auth::id(), 403);

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