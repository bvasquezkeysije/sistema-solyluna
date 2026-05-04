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
