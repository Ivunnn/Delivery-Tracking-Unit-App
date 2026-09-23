<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Unit::where('status', 'tersedia')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tipe_motor', 'like', "%{$search}%")
                  ->orWhere('warna', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $units  = $query->paginate(12)->withQueryString();

        // Tahun unik untuk filter
        $tahuns = Unit::where('status', 'tersedia')
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('pages.customer.katalog.index', [
            'title'  => 'Katalog Unit',
            'units'  => $units,
            'tahuns' => $tahuns,
        ]);
    }

    // ── Show --------------------------------------------------
    public function show(Unit $unit)
    {
        abort_if($unit->status !== 'tersedia', 404);

        // Cek customer sudah punya order aktif untuk unit ini
        $sudahOrder = Order::where('id_customer', Auth::id())
            ->where('id_unit', $unit->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        return view('pages.customer.katalog.show', [
            'title'      => $unit->tipe_motor,
            'unit'       => $unit,
            'sudahOrder' => $sudahOrder,
        ]);
    }

    // ── Order Unit ───────────────────────────────────────────
    public function order(Request $request, Unit $unit)
    {
        abort_if($unit->status !== 'tersedia', 404);

        // Cegah double order
        $sudahOrder = Order::where('id_customer', Auth::id())
            ->where('id_unit', $unit->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($sudahOrder) {
            return redirect()->route('customer.katalog.show', $unit)
                ->with('error', 'Kamu sudah memiliki order aktif untuk unit ini.');
        }

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        Order::create([
            'id_customer' => Auth::id(),
            'id_unit'     => $unit->id,
            'kode_order'  => Order::generateKode(),
            'catatan'     => $request->catatan ?? null,
            'status'      => 'menunggu',
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order berhasil diajukan. Tunggu konfirmasi dari admin.');
    }
}