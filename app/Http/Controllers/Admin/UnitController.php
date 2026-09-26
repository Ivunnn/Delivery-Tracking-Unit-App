<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Unit::latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_rangka', 'like', "%{$search}%")
                    ->orWhere('tipe_motor', 'like', "%{$search}%")
                    ->orWhere('warna', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $units = $query->paginate(10)->withQueryString();

        return view('pages.admin.units.index', [
            'title' => 'Data Unit',
            'units' => $units,
        ]);
    }

    // ── Create ───────────────────────────────────────────────
    public function create()
    {
        return view('pages.admin.units.create', [
            'title' => 'Tambah Unit',
        ]);
    }

    // ── Store ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_rangka' => ['required', 'string', 'max:50', 'unique:units,no_rangka'],
            'tipe_motor' => ['required', 'string', 'max:100'],
            'warna' => ['required', 'string', 'max:50'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'harga' => ['nullable', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'no_rangka.required' => 'Nomor rangka wajib diisi.',
            'no_rangka.unique' => 'Nomor rangka sudah terdaftar.',
            'tipe_motor.required' => 'Tipe motor wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('units', 'public');
        }

        $validated['status'] = 'tersedia';

        Unit::create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }


    // ── Edit ─────────────────────────────────────────────────
    public function edit(Unit $unit)
    {
        return view('pages.admin.units.edit', [
            'title' => 'Edit Unit',
            'unit' => $unit,
        ]);
    }

    // ── Update ───────────────────────────────────────────────
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'no_rangka' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'no_rangka')->ignore($unit->id)
            ],
            'tipe_motor' => ['required', 'string', 'max:100'],
            'warna' => ['required', 'string', 'max:50'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'harga' => ['nullable', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in(['tersedia', 'dipesan', 'dikirim', 'terjual'])],
            'keterangan' => ['nullable', 'string'],
        ], [
            'no_rangka.required' => 'Nomor rangka wajib diisi.',
            'no_rangka.unique' => 'Nomor rangka sudah dipakai unit lain.',
            'tipe_motor.required' => 'Tipe motor wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // Upload foto baru, hapus yang lama
        if ($request->hasFile('foto')) {
            if ($unit->foto) {
                Storage::disk('public')->delete($unit->foto);
            }
            $validated['foto'] = $request->file('foto')->store('units', 'public');
        }

        // Hapus foto kalau centang hapus
        if ($request->boolean('hapus_foto') && $unit->foto) {
            Storage::disk('public')->delete($unit->foto);
            $validated['foto'] = null;
        }

        $unit->update($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    // ── Destroy ──────────────────────────────────────────────
    public function destroy(Unit $unit)
    {
        // Cegah hapus unit yang sedang dalam proses
        if (in_array($unit->status, ['dipesan', 'dikirim'])) {
            return redirect()->route('admin.units.index')
                ->with('error', 'Unit tidak bisa dihapus karena sedang dalam proses pengiriman atau pemesanan.');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}