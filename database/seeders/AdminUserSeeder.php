<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        // Buat Role
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'customer']);

        // Buat Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'nama_lengkap' => 'Admin Jambur',
                'telepon' => '081234567890',
                'password' => Hash::make('admin123'),
            ]
        );
        $adminUser->assignRole('admin');

        // Buat Customer User
        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'username' => 'customer',
                'nama_lengkap' => 'Customer Jambur',
                'telepon' => '081234567891',
                'password' => Hash::make('customer123'),
            ]
        );
        $customerUser->assignRole('customer');
    }
}
