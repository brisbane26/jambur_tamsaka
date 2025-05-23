<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['id' =>1, 'nama_kategori' => 'Gedung'], 
            ['id' =>2, 'nama_kategori' => 'Catering'],
            ['id' =>3, 'nama_kategori' => 'Salon'],
            ['id' =>4, 'nama_kategori' => 'Dekorasi'],
            ['id' =>5, 'nama_kategori' => 'Musik'], 
            ['id' =>6, 'nama_kategori' => 'Dokumentasi'], 
            ['id' =>7, 'nama_kategori' => 'Lainnya'], 
        ];

        foreach ($kategori as $data) {
        Kategori::create($data);
        }
    }
}
