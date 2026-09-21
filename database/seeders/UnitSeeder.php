<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['no_rangka' => 'MH1JFP110PK000001', 'tipe_motor' => 'Honda Beat ESP', 'warna' => 'Hitam', 'tahun' => 2024, 'harga' => 17500000, 'status' => 'tersedia'],
            ['no_rangka' => 'MH1JFP110PK000002', 'tipe_motor' => 'Honda Beat ESP', 'warna' => 'Putih', 'tahun' => 2024, 'harga' => 17500000, 'status' => 'tersedia'],
            ['no_rangka' => 'MH1JFP110PK000003', 'tipe_motor' => 'Honda Vario 125', 'warna' => 'Merah', 'tahun' => 2024, 'harga' => 22000000, 'status' => 'tersedia'],
            ['no_rangka' => 'MH1JFP110PK000004', 'tipe_motor' => 'Honda Vario 160', 'warna' => 'Biru', 'tahun' => 2024, 'harga' => 28000000, 'status' => 'tersedia'],
            ['no_rangka' => 'MH1JFP110PK000005', 'tipe_motor' => 'Honda Scoopy', 'warna' => 'Krem', 'tahun' => 2024, 'harga' => 23500000, 'status' => 'dipesan'],
        ];

        foreach ($units as $unit) {
            Unit::create(array_merge($unit, ['keterangan' => null]));
        }
    }
}