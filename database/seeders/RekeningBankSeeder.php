<?php

namespace Database\Seeders;

use App\Models\RekeningBank;
use Illuminate\Database\Seeder;

class RekeningBankSeeder extends Seeder
{
    public function run(): void
    {
        $rekening = [
            [
                'nama_bank'   => 'BRI',
                'no_rekening' => '1234-5678-9012-3456',
                'atas_nama'   => 'CV. Anugerah Bojonegoro',
                'is_active'   => true,
            ],
            [
                'nama_bank'   => 'BCA',
                'no_rekening' => '9876-5432-1098-7654',
                'atas_nama'   => 'CV. Anugerah Bojonegoro',
                'is_active'   => true,
            ],
            [
                'nama_bank'   => 'Mandiri',
                'no_rekening' => '1357-2468-1357-2468',
                'atas_nama'   => 'CV. Anugerah Bojonegoro',
                'is_active'   => false,
            ],
        ];

        foreach ($rekening as $r) {
            RekeningBank::create($r);
        }
    }
}