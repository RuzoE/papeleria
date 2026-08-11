<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@papeleria.local'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin1234!'),
                'role' => 'admin',
                'status' => true,
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Usuario administrador creado: admin@papeleria.local');
        $this->command->warn('⚠  Cambie la contraseña inicial antes de producción.');
    }
}
