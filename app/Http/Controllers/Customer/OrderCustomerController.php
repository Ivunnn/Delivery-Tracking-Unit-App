<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCustomerController extends Controller
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
                'catatan',
                'status',
                'alasan_tolak',
                'created_at',
            ])
            ->with([
                'unit:id,tipe_motor,no_rangka,warna,tahun,harga',
                'invoice:id,id_order,status_bayar,total',
                'pengiriman:id,id_order,kode_pengiriman,status',
            ])
            ->where('id_customer', Auth::id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('pages.customer.orders.index', [
            'title'  => 'Order Saya',
            'orders' => $orders,
        ]);
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Order $order)
    {
        abort_if($order->id_customer !== Auth::id(), 403);

        $order->load([
            'unit:id,tipe_motor,no_rangka,warna,tahun,harga',
            'invoice:id,id_order,kode_invoice,total,status_bayar',
            'pengiriman:id,id_order,kode_pengiriman,tanggal_kirim,estimasi_tiba,status',
            'pengiriman.trackings:id,id_pengiriman,status_tracking,jam_update',
        ]);

        return view('pages.customer.orders.show', [
            'title' => 'Detail Order',
            'order' => $order,
        ]);
    }

    // ── Cancel ───────────────────────────────────────────────
    public function cancel(Order $order)
    {
        abort_if($order->id_customer !== Auth::id(), 403);
        abort_if($order->status !== 'menunggu', 403);

        $order->update(['status' => 'ditolak', 'alasan_tolak' => 'Dibatalkan oleh customer.']);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order berhasil dibatalkan.');
    }
}