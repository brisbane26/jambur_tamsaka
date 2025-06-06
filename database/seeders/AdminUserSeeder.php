<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        // Buat Roles (Gunakan firstOrCreate untuk mencegah duplikasi)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Buat Permissions
        $permissions = [
            'access dashboard',
            'access forms',
            'access tables',
            'access ui-elements',
            'access daftar-paket',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign Permissions ke Role
        $adminRole->syncPermissions(['access dashboard', 'access forms', 'access tables', 'access ui-elements', 'access daftar-paket']);
        $customerRole->syncPermissions(['access dashboard', 'access forms', 'access tables', 'access daftar-paket']);

        // Buat Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'nama_lengkap' => 'Admin Jambur',
                'telepon' => '081234567890',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // Buat Customer User
$customerUsers = [
    [
        'email' => 'customer@example.com',
        'username' => 'customer',
        'nama_lengkap' => 'Customer Jambur',
        'telepon' => '081234567891',
        'password' => Hash::make('customer123'),
        'email_verified_at' => now(),
    ],
    [
        'email' => 'petong@example.com',
        'username' => 'petong',
        'nama_lengkap' => 'Petra Igor',
        'telepon' => '081234567892',
        'password' => Hash::make('petong123'),
        'email_verified_at' => now(),
    ],
    [
        'email' => 'bane@example.com',
        'username' => 'bane',
        'nama_lengkap' => 'Bane Jovan',
        'telepon' => '081234567893',
        'password' => Hash::make('bane123'),
        'email_verified_at' => now(),
    ],
];

foreach ($customerUsers as $customerData) {
    $user = User::firstOrCreate(
        ['email' => $customerData['email']],
        $customerData
    );

    $user->assignRole('customer');
}

        
    }
}
