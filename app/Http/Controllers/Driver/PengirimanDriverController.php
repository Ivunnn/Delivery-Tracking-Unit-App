<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\BuktiPengiriman;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Pengiriman;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengirimanDriverController extends Controller
{
    // ── Ambil driver yang login ──────────────────────────────
    private function getDriver(): Driver
    {
        return Driver::where('id_user', Auth::id())->firstOrFail();
    }

    // ── Dashboard ────────────────────────────────────────────
    public function dashboard()
    {
        $driver = $this->getDriver();

        $aktif = Pengiriman::where('id_driver', $driver->id)
            ->whereNotIn('status', ['selesai'])
            ->with(['order.customer', 'order.unit', 'trackingTerakhir'])
            ->latest()
            ->get();

        $totalSelesai = Pengiriman::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->count();

        $totalSemua = Pengiriman::where('id_driver', $driver->id)->count();

        return view('pages.driver.dashboard', [
            'title'        => 'Dashboard Driver',
            'driver'       => $driver,
            'aktif'        => $aktif,
            'totalSelesai' => $totalSelesai,
            'totalSemua'   => $totalSemua,
        ]);
    }

    // ── Pengiriman Aktif ─────────────────────────────────────
    public function aktif()
    {
        $driver = $this->getDriver();

        $pengiriman = Pengiriman::where('id_driver', $driver->id)
            ->whereNotIn('status', ['selesai'])
            ->with(['order.customer', 'order.unit', 'trackingTerakhir'])
            ->latest()
            ->paginate(10);

        return view('pages.driver.pengiriman.aktif', [
            'title'      => 'Pengiriman Aktif',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── Riwayat Pengiriman ───────────────────────────────────
    public function riwayat(Request $request)
    {
        $driver = $this->getDriver();

        $query = Pengiriman::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->with(['order.customer', 'order.unit'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengiriman', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('order.customer', fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('nama_toko', 'like', "%{$search}%"));
            });
        }

        $pengiriman = $query->paginate(10)->withQueryString();

        return view('pages.driver.pengiriman.riwayat', [
            'title'      => 'Riwayat Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }

    // ── Detail Pengiriman ────────────────────────────────────
    public function show(Pengiriman $pengiriman)
    {
        $driver = $this->getDriver();

        // Pastikan pengiriman milik driver yang login
        abort_if($pengiriman->id_driver !== $driver->id, 403);

        $pengiriman->load([
            'order.customer',
            'order.unit',
            'trackings',
            'buktiPengiriman',
        ]);

        return view('pages.driver.pengiriman.show', [
            'title'      => 'Detail Pengiriman',
            'pengiriman' => $pengiriman,
            'driver'     => $driver,
        ]);
    }

    // ── Update Status + Lokasi ───────────────────────────────
    public function updateStatus(Request $request, Pengiriman $pengiriman)
    {
        $driver = $this->getDriver();

        abort_if($pengiriman->id_driver !== $driver->id, 403);
        abort_if($pengiriman->status === 'selesai', 403);

        $validated = $request->validate([
            'status'         => ['required', 'in:berangkat,dalam_perjalanan,tiba,selesai'],
            'lokasi'         => ['nullable', 'string', 'max:255'],
            'lat'            => ['nullable', 'numeric'],
            'lng'            => ['nullable', 'numeric'],
            'catatan'        => ['nullable', 'string', 'max:255'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ]);

        // Simpan tracking
        Tracking::create([
            'id_pengiriman'  => $pengiriman->id,
            'status_tracking'=> $this->labelStatus($validated['status']),
            'lat'            => $validated['lat'] ?? null,
            'lng'            => $validated['lng'] ?? null,
            'lokasi'         => $validated['lokasi'] ?? null,
            'catatan'        => $validated['catatan'] ?? null,
            'jam_update'     => now(),
        ]);

        // Update status pengiriman
        $pengiriman->update(['status' => $validated['status']]);

        // Kalau selesai, driver kembali tersedia
        if ($validated['status'] === 'selesai') {
            $driver->update(['status' => 'tersedia']);
            $pengiriman->order->unit->update(['status' => 'terjual']);
            $pengiriman->order->update(['status' => 'selesai']);
        }

        return redirect()->route('driver.pengiriman.show', $pengiriman)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    // ── Upload Bukti ─────────────────────────────────────────
    public function uploadBukti(Request $request, Pengiriman $pengiriman)
    {
        $driver = $this->getDriver();

        abort_if($pengiriman->id_driver !== $driver->id, 403);

        $request->validate([
            'foto_bukti'  => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'keterangan'  => ['nullable', 'string', 'max:255'],
        ], [
            'foto_bukti.required' => 'Foto bukti wajib diupload.',
            'foto_bukti.image'    => 'File harus berupa gambar.',
            'foto_bukti.mimes'    => 'Format harus JPG, JPEG, atau PNG.',
            'foto_bukti.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Hapus bukti lama kalau ada
        if ($pengiriman->buktiPengiriman) {
            Storage::disk('public')->delete($pengiriman->buktiPengiriman->foto_bukti);
            $pengiriman->buktiPengiriman->delete();
        }

        $path = $request->file('foto_bukti')->store('bukti-pengiriman', 'public');

        BuktiPengiriman::create([
            'id_pengiriman' => $pengiriman->id,
            'foto_bukti'    => $path,
            'keterangan'    => $request->keterangan ?? null,
            'waktu_upload'  => now(),
        ]);

        return redirect()->route('driver.pengiriman.show', $pengiriman)
            ->with('success', 'Bukti pengiriman berhasil diupload.');
    }

    // ── Helper label status ──────────────────────────────────
    private function labelStatus(string $status): string
    {
        return match($status) {
            'berangkat'        => 'Berangkat dari gudang',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'tiba'             => 'Tiba di Lokasi',
            'selesai'          => 'Pengiriman Selesai',
            default            => $status,
        };
    }
}