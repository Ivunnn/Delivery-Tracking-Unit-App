<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Driver::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('no_ktp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drivers = $query->paginate(10)->withQueryString();

        return view('pages.admin.drivers.index', [
            'title'   => 'Data Driver',
            'drivers' => $drivers,
        ]);
    }

    // ── Create ───────────────────────────────────────────────
    public function create()
    {
        return view('pages.admin.drivers.create', [
            'title' => 'Tambah Driver',
        ]);
    }

    // ── Store ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'no_ktp'   => ['nullable', 'string', 'max:20', 'unique:drivers,no_ktp'],
            'no_sim'   => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'phone.required'    => 'Nomor HP wajib diisi.',
            'no_ktp.unique'     => 'No KTP sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'password'  => Hash::make($validated['password']),
                'role'      => 'driver',
                'is_active' => true,
            ]);

            Driver::create([
                'id_user' => $user->id,
                'no_ktp'  => $validated['no_ktp'] ?? null,
                'no_sim'  => $validated['no_sim'] ?? null,
                'status'  => 'tersedia',
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil ditambahkan.');
    }

    // ── Edit ─────────────────────────────────────────────────
    public function edit(Driver $driver)
    {
        $driver->load('user');

        return view('pages.admin.drivers.edit', [
            'title'  => 'Edit Driver',
            'driver' => $driver,
        ]);
    }

    // ── Update ───────────────────────────────────────────────
    public function update(Request $request, Driver $driver)
    {
        $driver->load('user');

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email',
                            Rule::unique('users', 'email')->ignore($driver->user->id)],
            'phone'     => ['required', 'string', 'max:20'],
            'no_ktp'    => ['nullable', 'string', 'max:20',
                            Rule::unique('drivers', 'no_ktp')->ignore($driver->id)],
            'no_sim'    => ['nullable', 'string', 'max:20'],
            'status'    => ['required', Rule::in(['tersedia', 'bertugas'])],
            'is_active' => ['required', 'boolean'],
            'password'  => ['nullable', 'string', 'min:8'],
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah dipakai akun lain.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'no_ktp.unique'  => 'No KTP sudah dipakai driver lain.',
            'status.required'=> 'Status wajib dipilih.',
            'password.min'   => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($validated, $driver) {
            $userData = [
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'is_active' => $validated['is_active'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $driver->user->update($userData);

            $driver->update([
                'no_ktp' => $validated['no_ktp'] ?? null,
                'no_sim' => $validated['no_sim'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Data driver berhasil diperbarui.');
    }

    // ── Destroy ──────────────────────────────────────────────
    public function destroy(Driver $driver)
    {
        if ($driver->status === 'bertugas') {
            return redirect()->route('admin.drivers.index')
                ->with('error', 'Driver tidak bisa dihapus karena sedang bertugas.');
        }

        DB::transaction(function () use ($driver) {
            $user = $driver->user;
            $driver->delete();
            $user->delete();
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil dihapus.');
    }
}