<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@popeyecargo.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('popeye2025'),
                'email_verified_at' => now(),
                'rol' => 'admin',
            ]
        );
    }
}
