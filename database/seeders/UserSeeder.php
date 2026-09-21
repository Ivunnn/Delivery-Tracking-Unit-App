<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                'name'  => 'Budi Santoso',
                'email' => 'budi@anugerah.com',
                'phone' => '082111222333',
            ],
            [
                'name'  => 'Agus Prasetyo',
                'email' => 'agus@anugerah.com',
                'phone' => '082444555666',
            ],
            [
                'name'  => 'Roni Hidayat',
                'email' => 'roni@anugerah.com',
                'phone' => '082777888999',
            ],
        ];

        foreach ($drivers as $driver) {
            User::create([
                'name'      => $driver['name'],
                'email'     => $driver['email'],
                'phone'     => $driver['phone'],
                'password'  => Hash::make('password'),
                'role'      => 'driver',
                'is_active' => true,
            ]);
        }

        // ── CUSTOMER ───────────────────────────────────────────
        $customers = [
            [
                'name'  => 'Dealer Lamongan Jaya',
                'email' => 'lamongan@dealer.com',
                'phone' => '083111222333',
            ],
            [
                'name'  => 'Dealer Tuban Motor',
                'email' => 'tuban@dealer.com',
                'phone' => '083444555666',
            ],
            [
                'name'  => 'Dealer Cepu Abadi',
                'email' => 'cepu@dealer.com',
                'phone' => '083777888999',
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
            ]);
        }
    }
}