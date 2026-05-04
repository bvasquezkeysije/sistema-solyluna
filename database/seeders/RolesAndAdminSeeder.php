<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'recepcionista']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@solyluna.com'],
            [
                'name' => 'Administrador',
                'username' => 'admin',
                'password' => Hash::make('admin12345'),
            ]
        );
        if (empty($adminUser->username)) {
            $adminUser->username = 'admin';
            $adminUser->save();
        }

        $adminUser->assignRole($adminRole);
    }
}
