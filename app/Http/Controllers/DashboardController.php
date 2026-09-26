<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Pengiriman;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        // Statistik utama
        $totalCustomer = User::customers()->count();
        $totalOrder = Order::count();
        $totalUnit = Unit::count();
        $totalDriver = Driver::count();
        $totalPendapatan = Invoice::where('status_bayar', 'sudah_bayar')->sum('total');

        // Order menunggu persetujuan
        $orderMenunggu = Order::menunggu()->count();

        // Pengiriman aktif
        $pengirimanAktif = Pengiriman::whereNotIn('status', ['selesai'])->count();

        // Bukti transfer menunggu verifikasi
        $buktiMenunggu = Invoice::where('status_verifikasi', 'menunggu_verifikasi')->count();

        // Recent orders (5 terbaru)
        $recentOrders = Order::with(['customer', 'unit'])
            ->latest()
            ->take(5)
            ->get();

        // Pengiriman aktif list (5 terbaru)
        $pengirimanAktifList = Pengiriman::with(['order.customer', 'order.unit', 'driver.user'])
            ->whereNotIn('status', ['selesai'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', [
            'title' => 'Dashboard',
            'totalCustomer' => $totalCustomer,
            'totalOrder' => $totalOrder,
            'totalUnit' => $totalUnit,
            'totalDriver' => $totalDriver,
            'totalPendapatan' => $totalPendapatan,
            'orderMenunggu' => $orderMenunggu,
            'pengirimanAktif' => $pengirimanAktif,
            'buktiMenunggu' => $buktiMenunggu,
            'recentOrders' => $recentOrders,
            'pengirimanAktifList' => $pengirimanAktifList,
        ]);
    }
    public function driver()
    {
        $driver = Driver::where('id_user', Auth::id())->firstOrFail();

        // Statistik
        $totalAktif = Pengiriman::where('id_driver', $driver->id)
            ->whereNotIn('status', ['selesai'])
            ->count();

        $totalSelesai = Pengiriman::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->count();

        $totalSemua = Pengiriman::where('id_driver', $driver->id)->count();

        // Pengiriman aktif hari ini
        $aktifHariIni = Pengiriman::where('id_driver', $driver->id)
            ->whereNotIn('status', ['selesai'])
            ->with(['order.customer', 'order.unit', 'trackings'])
            ->latest()
            ->take(5)
            ->get();

        // Riwayat terakhir
        $riwayatTerakhir = Pengiriman::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->with(['order.customer', 'order.unit'])
            ->latest()
            ->take(3)
            ->get();

        return view('pages.driver.dashboard', [
            'title' => 'Dashboard',
            'driver' => $driver,
            'totalAktif' => $totalAktif,
            'totalSelesai' => $totalSelesai,
            'totalSemua' => $totalSemua,
            'aktifHariIni' => $aktifHariIni,
            'riwayatTerakhir' => $riwayatTerakhir,
        ]);
    }

    public function customer()
    {
        $customerId = Auth::id();
        $ordersQuery = Order::where('id_customer', $customerId);

        $recentOrders = (clone $ordersQuery)
            ->select([
                'id',
                'id_customer',
                'id_unit',
                'kode_order',
                'status',
                'created_at',
            ])
            ->with([
                'unit:id,tipe_motor,warna,harga',
                'invoice:id,id_order,total,status_bayar',
                'pengiriman:id,id_order,kode_pengiriman,status',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.customer.dashboard', [
            'title' => 'Dashboard Customer',
            'user' => Auth::user(),
            'totalOrders' => (clone $ordersQuery)->count(),
            'activeOrders' => (clone $ordersQuery)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->count(),
            'completedOrders' => (clone $ordersQuery)
                ->where('status', 'selesai')
                ->count(),
            'unpaidInvoices' => (clone $ordersQuery)
                ->whereHas('invoice', fn($query) => $query->where('status_bayar', 'belum_bayar'))
                ->count(),
            'recentOrders' => $recentOrders,
        ]);
    }
}