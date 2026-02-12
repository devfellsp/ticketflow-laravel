<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Chamar UserSeeder
        $this->call(UserSeeder::class);

        // Criar 10 tickets de exemplo usando Factory
        Ticket::factory()->count(10)->create();
    }
}