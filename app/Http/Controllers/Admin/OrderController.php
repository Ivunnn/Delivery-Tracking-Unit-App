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
        $query = Order::query()
            ->select([
                'id',
                'id_customer',
                'id_unit',
                'kode_order',
                'status',
                'created_at',
            ])
            ->with([
                'customer:id,name,nama_toko',
                'unit:id,tipe_motor,no_rangka,harga',
                'invoice:id,id_order,total',
            ])
            ->latest();

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
            'title'        => 'Kelola Order',
            'orders'       => $orders,
            'pendingCount' => Order::menunggu()->count(),
        ]);
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Order $order)
    {
        $order->load([
            'customer:id,name,email,phone,nama_toko,kota,alamat',
            'unit:id,tipe_motor,no_rangka,warna,tahun,harga',
            'invoice:id,id_order,kode_invoice,harga,biaya_pengiriman,total,status_bayar,paid_at',
            'pengiriman:id,id_order,id_driver,kode_pengiriman,tanggal_kirim,estimasi_tiba,tujuan,status',
            'pengiriman.driver:id,id_user,no_ktp,no_sim,status',
            'pengiriman.driver.user:id,name,email,phone',
        ]);

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

        $order->load('unit:id,harga,status');

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