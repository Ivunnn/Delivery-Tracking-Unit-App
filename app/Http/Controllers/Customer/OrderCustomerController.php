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
        $query = Order::with(['unit', 'invoice', 'pengiriman'])
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

        $order->load(['unit', 'invoice', 'pengiriman.driver.user', 'pengiriman.trackingTerakhir']);

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