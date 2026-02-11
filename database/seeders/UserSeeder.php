<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@teste.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Usuário comum
        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@teste.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Técnico
        User::create([
            'name' => 'Técnico Suporte',
            'email' => 'tecnico@teste.com',
            'password' => Hash::make('password'),
            'role' => 'tecnico',
        ]);
    }
}