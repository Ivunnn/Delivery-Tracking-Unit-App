<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Pengiriman::with([
            'order.customer',
            'order.unit',
            'driver.user',
            'trackingTerakhir',
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengiriman', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('order.customer', fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('nama_toko', 'like', "%{$search}%"))
                  ->orWhereHas('driver.user', fn($q) =>
                        $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengiriman = $query->paginate(10)->withQueryString();

        return view('pages.admin.pengiriman.index', [
            'title'      => 'Kelola Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── Create ───────────────────────────────────────────────
    public function create(Request $request)
    {
        // Order yang sudah disetujui tapi belum punya pengiriman
        $orders = Order::with(['customer', 'unit'])
            ->where('status', 'disetujui')
            ->whereDoesntHave('pengiriman')
            ->latest()
            ->get();

        // Driver yang tersedia
        $drivers = Driver::with('user')
            ->where('status', 'tersedia')
            ->get();

        // Pre-select order jika dari halaman show order
        $selectedOrder = null;
        if ($request->filled('order_id')) {
            $selectedOrder = Order::with(['customer', 'unit'])
                ->find($request->order_id);
        }

        return view('pages.admin.pengiriman.create', [
            'title'         => 'Buat Pengiriman',
            'orders'        => $orders,
            'drivers'       => $drivers,
            'selectedOrder' => $selectedOrder,
        ]);
    }

    // ── Store ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_order'      => ['required', 'exists:orders,id'],
            'id_driver'     => ['required', 'exists:drivers,id'],
            'tanggal_kirim' => ['required', 'date'],
            'estimasi_tiba' => ['nullable', 'date', 'after_or_equal:tanggal_kirim'],
            'tujuan'        => ['required', 'string', 'max:255'],
        ], [
            'id_order.required'      => 'Order wajib dipilih.',
            'id_order.exists'        => 'Order tidak valid.',
            'id_driver.required'     => 'Driver wajib dipilih.',
            'id_driver.exists'       => 'Driver tidak valid.',
            'tanggal_kirim.required' => 'Tanggal kirim wajib diisi.',
            'estimasi_tiba.after_or_equal' => 'Estimasi tiba tidak boleh sebelum tanggal kirim.',
            'tujuan.required'        => 'Tujuan pengiriman wajib diisi.',
        ]);

        // Cek order belum punya pengiriman
        $order = Order::findOrFail($validated['id_order']);
        if ($order->pengiriman) {
            return back()->with('error', 'Order ini sudah memiliki pengiriman.');
        }

        DB::transaction(function () use ($validated, $order) {
            // Buat pengiriman
            $pengiriman = Pengiriman::create([
                'id_order'        => $order->id,
                'id_driver'       => $validated['id_driver'],
                'kode_pengiriman' => Pengiriman::generateKode(),
                'tanggal_kirim'   => $validated['tanggal_kirim'],
                'estimasi_tiba'   => $validated['estimasi_tiba'] ?? null,
                'tujuan'          => $validated['tujuan'],
                'status'          => 'menunggu',
            ]);

            // Update status driver jadi bertugas
            $driver = Driver::findOrFail($validated['id_driver']);
            $driver->update(['status' => 'bertugas']);

            // Update status unit jadi dikirim
            $order->unit->update(['status' => 'dikirim']);
        });

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Pengiriman $pengiriman)
    {
        $pengiriman->load([
            'order.customer',
            'order.unit',
            'order.invoice',
            'driver.user',
            'trackings',
            'buktiPengiriman',
        ]);

        return view('pages.admin.pengiriman.show', [
            'title'      => 'Detail Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }
}