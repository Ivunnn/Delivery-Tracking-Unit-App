<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekeningBank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RekeningBankController extends Controller
{
    public function index()
    {
        $rekening = RekeningBank::latest()->get();

        return view('pages.admin.rekening.index', [
            'title'   => 'Rekening Bank',
            'rekening' => $rekening,
        ]);
    }

    public function create()
    {
        return view('pages.admin.rekening.create', [
            'title' => 'Tambah Rekening',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bank'   => ['required', 'string', 'max:50'],
            'no_rekening' => ['required', 'string', 'max:30'],
            'atas_nama'   => ['required', 'string', 'max:100'],
            'is_active'   => ['required', 'boolean'],
        ], [
            'nama_bank.required'   => 'Nama bank wajib diisi.',
            'no_rekening.required' => 'Nomor rekening wajib diisi.',
            'atas_nama.required'   => 'Atas nama wajib diisi.',
        ]);

        RekeningBank::create($validated);

        return redirect()->route('admin.rekening.index')
            ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function edit(RekeningBank $rekening)
    {
        return view('pages.admin.rekening.edit', [
            'title'    => 'Edit Rekening',
            'rekening' => $rekening,
        ]);
    }

    public function update(Request $request, RekeningBank $rekening)
    {
        $validated = $request->validate([
            'nama_bank'   => ['required', 'string', 'max:50'],
            'no_rekening' => ['required', 'string', 'max:30'],
            'atas_nama'   => ['required', 'string', 'max:100'],
            'is_active'   => ['required', 'boolean'],
        ]);

        $rekening->update($validated);

        return redirect()->route('admin.rekening.index')
            ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    public function destroy(RekeningBank $rekening)
    {
        $rekening->delete();

        return redirect()->route('admin.rekening.index')
            ->with('success', 'Rekening bank berhasil dihapus.');
    }
}