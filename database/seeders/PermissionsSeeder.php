<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     //
    // }
    public function run()
    {
        // Buat Role
        $admin = Role::create(['name' => 'admin']);
        $customer = Role::create(['name' => 'customer']);

        // Buat Permissions
        $permissions = [
            'access dashboard',
            'access forms',
            'access tables',
            'access ui elements'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo($permissions);
        $customer->givePermissionTo(['access dashboard', 'access forms', 'access tables']);
    }
}
