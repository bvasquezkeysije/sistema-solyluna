<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['is_active' => true]);
        $recepcionistaRole = Role::updateOrCreate(['name' => 'recepcionista'], ['is_active' => true]);

        $permissions = [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.deactivate',
            'roles.manage',
            'clients.view',
            'clients.create',
            'clients.edit',
            'products.view',
            'products.create',
            'products.edit',
            'rooms.view',
            'rooms.create',
            'rooms.edit',
            'floors.manage',
            'sales.view',
            'sales.create',
            'sales.edit',
            'settings.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole->syncPermissions($permissions);
        $recepcionistaRole->syncPermissions([
            'dashboard.view',
            'clients.view',
            'clients.create',
            'products.view',
            'rooms.view',
            'sales.view',
            'sales.create',
        ]);

        $adminUsers = [
            ['username' => 'bvasquezkeysije', 'email' => 'bvasquezkeysije@uss.edu.pe', 'password' => '76636255'],
            ['username' => 'dgarciabriggitl', 'email' => 'dgarciabriggitl@uss.edu.pe', 'password' => '76465678'],
            ['username' => 'vquispejorgetom', 'email' => 'vquispejorgetom@uss.edu.pe', 'password' => '72838203'],
            ['username' => 'cleonalexandgra', 'email' => 'cleonalexandgra@uss.edu.pe', 'password' => '73149801'],
        ];

        foreach ($adminUsers as $adminData) {
            $adminUser = User::updateOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['username'],
                    'username' => $adminData['username'],
                    'password' => Hash::make($adminData['password']),
                ]
            );

            $adminUser->assignRole($adminRole);
        }
    }
}
