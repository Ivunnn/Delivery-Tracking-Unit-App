<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'title'    => 'Invoice Saya',
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
            'title'   => 'Invoice ' . $invoice->kode_invoice,
            'invoice' => $invoice,
        ]);
    }
}