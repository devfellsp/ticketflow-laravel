<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO']);
        // Pegar usuários existentes ou criar novos
        $solicitante = User::inRandomOrder()->first() ?? User::factory()->create();
        $responsavel = fake()->boolean(50) 
            ? (User::inRandomOrder()->first() ?? User::factory()->create())
            : null;
        
        return [
            'titulo' => fake()->sentence(6),
            'descricao' => fake()->paragraph(3),
            'status' => $status,
            'prioridade' => fake()->randomElement(['BAIXA', 'MEDIA', 'ALTA']),
            'solicitante_id' => $solicitante->id,
            'responsavel_id' => $responsavel?->id,
            'resolved_at' => $status === 'RESOLVIDO' ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }
}