<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $recentOrders = Order::with(['customer', 'unit', 'invoice'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Order $order, int $index): array {
                $status = match ($order->status) {
                    'selesai' => 'Delivered',
                    'ditolak' => 'Canceled',
                    default => 'Pending',
                };

                return [
                    'name' => $order->unit?->tipe_motor ?? $order->kode_order,
                    'variants' => 1,
                    'image' => '/images/product/product-0' . (($index % 5) + 1) . '.jpg',
                    'category' => $order->customer?->name ?? 'Customer',
                    'price' => 'Rp ' . number_format(
                        (float) ($order->invoice?->total ?? $order->unit?->harga ?? 0),
                        0,
                        ',',
                        '.'
                    ),
                    'status' => $status,
                ];
            })
            ->all();

        $customerCount = User::customers()->count();

        $customersByCity = User::customers()
            ->selectRaw("COALESCE(kota, 'Unknown') as name, COUNT(*) as customers")
            ->groupBy('kota')
            ->orderByDesc('customers')
            ->get();

        return view('pages.admin.dashboard', [
            'title' => 'Dashboard Admin',
            'user' => Auth::user(),
            'customerCount' => $customerCount,
            'orderCount' => Order::count(),
            'unitCount' => Unit::count(),
            'driverCount' => Driver::count(),
            'revenue' => Invoice::where('status_bayar', 'sudah_bayar')->sum('total'),
            'recentOrders' => $recentOrders,
            'countries' => $customersByCity->map(fn($city) => [
                'name' => $city->name,
                'flag' => '/images/country/country-01.svg',
                'customers' => number_format($city->customers),
                'percentage' => $customerCount > 0
                    ? (int) round(($city->customers / $customerCount) * 100)
                    : 0,
            ])->all(),
        ]);
    }

    public function driver()
    {
        return view('pages.driver.dashboard', [
            'title' => 'Dashboard Driver',
            'user' => Auth::user(),
        ]);
    }

    public function customer()
    {
        return view('pages.customer.dashboard', [
            'title' => 'Dashboard Customer',
            'user' => Auth::user(),
        ]);
    }
}