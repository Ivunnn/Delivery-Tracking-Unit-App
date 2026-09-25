<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Invoice::with(['order.customer', 'order.unit'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_invoice', 'like', "%{$search}%")
                    ->orWhereHas('order', fn($q) =>
                        $q->where('kode_order', 'like', "%{$search}%"))
                    ->orWhereHas('order.customer', fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('nama_toko', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('pages.admin.invoices.index', [
            'title' => 'Invoice',
            'invoices' => $invoices,
        ]);
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'order.customer',
            'order.unit',
            'order.pengiriman.driver.user',
        ]);

        return view('pages.admin.invoices.show', [
            'title' => 'Detail Invoice',
            'invoice' => $invoice,
        ]);
    }

    // ── Konfirmasi Bayar ─────────────────────────────────────
    public function konfirmasiBayar(Invoice $invoice)
    {
        if ($invoice->status_bayar === 'sudah_bayar') {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Invoice ini sudah dikonfirmasi sebelumnya.');
        }

        $invoice->update([
            'status_bayar' => 'sudah_bayar',
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // ── Update Biaya Pengiriman ───────────────────────────────
    public function updateBiaya(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'biaya_pengiriman' => ['required', 'numeric', 'min:0'],
        ], [
            'biaya_pengiriman.required' => 'Biaya pengiriman wajib diisi.',
            'biaya_pengiriman.numeric' => 'Biaya pengiriman harus berupa angka.',
            'biaya_pengiriman.min' => 'Biaya pengiriman tidak boleh negatif.',
        ]);

        $invoice->update([
            'biaya_pengiriman' => $validated['biaya_pengiriman'],
            'total' => $invoice->harga + $validated['biaya_pengiriman'],
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Biaya pengiriman berhasil diperbarui.');
    }

    // ── Print ────────────────────────────────────────────────
    public function print(Invoice $invoice)
    {
        $invoice->load([
            'order.customer',
            'order.unit',
            'order.pengiriman',
        ]);

        return view('pages.admin.invoices.print', [
            'invoice' => $invoice,
        ]);
    }
    // ── Verifikasi Bukti Transfer ────────────────────────────────
    public function verifikasiBukti(Invoice $invoice)
    {
        if ($invoice->status_verifikasi !== 'menunggu_verifikasi') {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Bukti transfer tidak dalam status menunggu verifikasi.');
        }

        $invoice->update([
            'status_verifikasi' => 'diterima',
            'status_bayar' => 'sudah_bayar',
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Bukti transfer diterima. Invoice dikonfirmasi lunas.');
    }

    // ── Tolak Bukti Transfer ─────────────────────────────────────
    public function tolakBukti(Request $request, Invoice $invoice)
    {
        $request->validate([
            'catatan_tolak' => ['required', 'string', 'max:255'],
        ], [
            'catatan_tolak.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $invoice->update([
            'status_verifikasi' => 'ditolak',
            'catatan_tolak' => $request->catatan_tolak,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Bukti transfer ditolak. Customer perlu upload ulang.');
    }
}