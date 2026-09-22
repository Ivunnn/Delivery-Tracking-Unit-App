<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Driver;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ──────────────────────────────────────────────
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@anugerah.com',
            'phone'     => '081234567890',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ── DRIVER ─────────────────────────────────────────────
        $drivers = [
            [
                'name'   => 'Budi Santoso',
                'email'  => 'budi@anugerah.com',
                'phone'  => '082111222333',
                'no_ktp' => '3522010101900001',
                'no_sim' => 'C123456789',
            ],
            [
                'name'   => 'Agus Prasetyo',
                'email'  => 'agus@anugerah.com',
                'phone'  => '082444555666',
                'no_ktp' => '3522010101900002',
                'no_sim' => 'C987654321',
            ],
            [
                'name'   => 'Roni Hidayat',
                'email'  => 'roni@anugerah.com',
                'phone'  => '082777888999',
                'no_ktp' => '3522010101900003',
                'no_sim' => 'C111222333',
            ],
        ];

        foreach ($drivers as $data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'],
                'password'  => Hash::make('password'),
                'role'      => 'driver',
                'is_active' => true,
            ]);

            // Buat record di tabel drivers
            Driver::create([
                'id_user' => $user->id,
                'no_ktp'  => $data['no_ktp'],
                'no_sim'  => $data['no_sim'],
                'status'  => 'tersedia',
            ]);
        }

        // ── CUSTOMER ───────────────────────────────────────────
        $customers = [
            [
                'name'      => 'Dealer Lamongan Jaya',
                'email'     => 'lamongan@dealer.com',
                'phone'     => '083111222333',
                'nama_toko' => 'Dealer Lamongan Jaya',
                'kota'      => 'Lamongan',
                'alamat'    => 'Jl. Raya Lamongan No. 10',
            ],
            [
                'name'      => 'Dealer Tuban Motor',
                'email'     => 'tuban@dealer.com',
                'phone'     => '083444555666',
                'nama_toko' => 'Dealer Tuban Motor',
                'kota'      => 'Tuban',
                'alamat'    => 'Jl. Veteran No. 25, Tuban',
            ],
            [
                'name'      => 'Dealer Cepu Abadi',
                'email'     => 'cepu@dealer.com',
                'phone'     => '083777888999',
                'nama_toko' => 'Dealer Cepu Abadi',
                'kota'      => 'Cepu',
                'alamat'    => 'Jl. Diponegoro No. 5, Cepu',
            ],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name'      => $customer['name'],
                'email'     => $customer['email'],
                'phone'     => $customer['phone'],
                'password'  => Hash::make('password'),
                'role'      => 'customer',
                'is_active' => true,
                'nama_toko' => $customer['nama_toko'],
                'kota'      => $customer['kota'],
                'alamat'    => $customer['alamat'],
            ]);
        }
    }
}