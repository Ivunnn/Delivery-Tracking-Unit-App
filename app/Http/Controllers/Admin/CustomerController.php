<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::customers()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('nama_toko', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $customers = $query->paginate(10)->withQueryString();

        return view('pages.admin.customers.index', [
            'title'     => 'Data Customer',
            'customers' => $customers,
        ]);
    }

    // ── Create ───────────────────────────────────────────────
    public function create()
    {
        return view('pages.admin.customers.create', [
            'title' => 'Tambah Customer',
        ]);
    }

    // ── Store ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'phone'     => ['required', 'string', 'max:20'],
            'nama_toko' => ['nullable', 'string', 'max:100'],
            'alamat'    => ['nullable', 'string'],
            'kota'      => ['nullable', 'string', 'max:100'],
            'password'  => ['required', 'string', 'min:8'],
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'   => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'nama_toko' => $validated['nama_toko'] ?? null,
            'alamat'    => $validated['alamat'] ?? null,
            'kota'      => $validated['kota'] ?? null,
            'password'  => Hash::make($validated['password']),
            'role'      => 'customer',
            'is_active' => true,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    // ── Edit ─────────────────────────────────────────────────
    public function edit(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        return view('pages.admin.customers.edit', [
            'title'    => 'Edit Customer',
            'customer' => $customer,
        ]);
    }

    // ── Update ───────────────────────────────────────────────
    public function update(Request $request, User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email',
                            Rule::unique('users', 'email')->ignore($customer->id)],
            'phone'     => ['required', 'string', 'max:20'],
            'nama_toko' => ['nullable', 'string', 'max:100'],
            'alamat'    => ['nullable', 'string'],
            'kota'      => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
            'password'  => ['nullable', 'string', 'min:8'],
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah dipakai akun lain.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.min'   => 'Password minimal 8 karakter.',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'nama_toko' => $validated['nama_toko'] ?? null,
            'alamat'    => $validated['alamat'] ?? null,
            'kota'      => $validated['kota'] ?? null,
            'is_active' => $validated['is_active'],
        ];

        // Ganti password hanya kalau diisi
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    // ── Toggle Active ────────────────────────────────────────
    public function toggleActive(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $customer->update(['is_active' => ! $customer->is_active]);

        $status = $customer->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.customers.index')
            ->with('success', "Akun customer berhasil {$status}.");
    }

    // ── Destroy ──────────────────────────────────────────────
    public function destroy(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}