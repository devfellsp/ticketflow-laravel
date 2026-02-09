<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // 1. Criar Usuário Admin
    $admin = \App\Models\User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@teste.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // 2. Criar Usuário Comum
    $user = \App\Models\User::factory()->create([
        'name' => 'Usuário Comum',
        'email' => 'user@teste.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);

    // 3. Criar 10 chamados vinculados ao usuário comum
    \App\Models\Ticket::factory(10)->create([
        'solicitante_id' => $user->id,
    ]);
}
}
