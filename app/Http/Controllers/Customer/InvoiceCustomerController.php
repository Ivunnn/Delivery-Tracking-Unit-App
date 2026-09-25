<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceCustomerController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Invoice::whereHas('order', fn($q) =>
            $q->where('id_customer', Auth::id()))
            ->with(['order.unit'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('pages.customer.invoices.index', [
            'title' => 'Invoice Saya',
            'invoices' => $invoices,
        ]);
    }

    // ── Show ─────────────────────────────────────────────────
    public function show(Invoice $invoice)
    {
        abort_if($invoice->order->id_customer !== Auth::id(), 403);

        $invoice->load([
            'order.unit',
            'order.pengiriman.driver.user',
        ]);

        return view('pages.customer.invoices.show', [
            'title' => 'Invoice ' . $invoice->kode_invoice,
            'invoice' => $invoice,
        ]);
    }

    // ── Upload Bukti Transfer ────────────────────────────────────
    public function uploadBukti(Request $request, Invoice $invoice)
    {
        abort_if($invoice->order->id_customer !== Auth::id(), 403);
        abort_if($invoice->status_bayar === 'sudah_bayar', 403);

        $request->validate([
            'bukti_bayar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'bukti_bayar.required' => 'Foto bukti transfer wajib diupload.',
            'bukti_bayar.image' => 'File harus berupa gambar.',
            'bukti_bayar.mimes' => 'Format harus JPG, JPEG, atau PNG.',
            'bukti_bayar.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Hapus bukti lama kalau ada
        if ($invoice->bukti_bayar) {
            Storage::disk('public')->delete($invoice->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti-transfer', 'public');

        $invoice->update([
            'bukti_bayar' => $path,
            'tgl_upload_bukti' => now(),
            'status_verifikasi' => 'menunggu_verifikasi',
            'catatan_tolak' => null,
        ]);

        return redirect()->route('customer.invoices.show', $invoice)
            ->with('success', 'Bukti transfer berhasil diupload. Menunggu konfirmasi admin.');
    }
}