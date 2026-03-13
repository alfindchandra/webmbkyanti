<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $cashierRole = \Spatie\Permission\Models\Role::create(['name' => 'cashier']);

        // Create Admin User
        $admin = \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@tokoatik.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Cashier User
        $cashier = \App\Models\User::factory()->create([
            'name' => 'Mbk Yanti',
            'email' => 'atik@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $cashier->assignRole($cashierRole);
        $cashier = \App\Models\User::factory()->create([
            'name' => 'Alfin',
            'email' => 'alfin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $cashier->assignRole($cashierRole);
    }
}
