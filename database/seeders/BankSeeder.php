<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $banks = [
            [
                'nama_bank' => 'Mandiri',
                'nomor_akun' => '092 7840 1923 7422',
                'nama_pemilik' => 'Yehezkiel Situmorang', // Ganti dengan nama pemilik sebenarnya
                'logo' => 'bank-mandiri.svg',
                'deskripsi' => null,
                'created_at' => '2025-05-15 01:58:31',
                'updated_at' => '2025-05-15 01:58:31'
            ],
            [
                'nama_bank' => 'BRI',
                'nomor_akun' => '082 9192 9183 3041',
                'nama_pemilik' => 'Yehezkiel Situmorang', // Ganti dengan nama pemilik sebenarnya
                'logo' => 'bank-bri.svg',
                'deskripsi' => null,
                'created_at' => '2025-05-15 01:58:31',
                'updated_at' => '2025-05-15 01:58:31'
            ],
            [
                'nama_bank' => 'BCA',
                'nomor_akun' => '019 8272 8274 1234',
                'nama_pemilik' => 'Yehezkiel Situmorang', // Ganti dengan nama pemilik sebenarnya
                'logo' => 'bank-bca.svg',
                'deskripsi' => null,
                'created_at' => '2025-05-15 01:58:31',
                'updated_at' => '2025-05-15 01:58:31'
            ],
            [
                'nama_bank' => 'BNI',
                'nomor_akun' => '076 8291 6371 6279',
                'nama_pemilik' => 'Yehezkiel Situmorang', // Ganti dengan nama pemilik sebenarnya
                'logo' => 'bank-bni.svg',
                'deskripsi' => null,
                'created_at' => '2025-05-15 01:58:31',
                'updated_at' => '2025-05-15 01:58:31'
            ]
        ];

        DB::table('banks')->insert($banks);
    }
}
