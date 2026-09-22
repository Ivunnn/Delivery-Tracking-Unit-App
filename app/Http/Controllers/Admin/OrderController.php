<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Unit;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'unit', 'invoice'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('nama_toko', 'like', "%{$search}%"))
                  ->orWhereHas('unit', fn($q) =>
                        $q->where('tipe_motor', 'like', "%{$search}%")
                          ->orWhere('no_rangka', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('pages.admin.orders.index', [
            'title'  => 'Kelola Order',
            'orders' => $orders,
        ]);
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Order $order)
    {
        $order->load(['customer', 'unit', 'invoice', 'pengiriman.driver.user']);

        return view('pages.admin.orders.show', [
            'title' => 'Detail Order',
            'order' => $order,
        ]);
    }

    // ── Approve ──────────────────────────────────────────────
    public function approve(Order $order)
    {
        if ($order->status !== 'menunggu') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($order) {
            // Update status order
            $order->update([
                'status'      => 'disetujui',
                'approved_at' => now(),
            ]);

            // Update status unit jadi dipesan
            $order->unit->update(['status' => 'dipesan']);

            // Buat invoice otomatis
            $harga            = $order->unit->harga ?? 0;
            $biaya_pengiriman = 0;

            Invoice::create([
                'id_order'         => $order->id,
                'kode_invoice'     => Invoice::generateKode(),
                'harga'            => $harga,
                'biaya_pengiriman' => $biaya_pengiriman,
                'total'            => $harga + $biaya_pengiriman,
                'status_bayar'     => 'belum_bayar',
            ]);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order berhasil disetujui dan invoice otomatis dibuat.');
    }

    // ── Reject ───────────────────────────────────────────────
    public function reject(Request $request, Order $order)
    {
        if ($order->status !== 'menunggu') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan_tolak' => ['required', 'string', 'max:255'],
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $order->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil ditolak.');
    }
}