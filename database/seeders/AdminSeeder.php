<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin12345!'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->command->info('Admin berhasil dibuat atau diperbarui.');
    }
}