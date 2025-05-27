<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Bank;
use App\Models\Paket;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //     ]);
    // }
    // public function run()
    // {
    //     $this->call(AdminUserSeeder::class);
    // }
    public function run()
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(AdminUserSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(PaketSeeder::class);
        $this->call(PesananComboSeeder::class);
        // $this->call(PermissionsSeeder::class);
    }


}
