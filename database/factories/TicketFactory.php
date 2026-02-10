<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'titulo' => fake()->sentence(4), // Gera um título aleatório
        'descricao' => fake()->paragraph(3), // Gera uma descrição
        'status' => 'ABERTO',
        'prioridade' => fake()->randomElement(['BAIXA', 'MEDIA', 'ALTA']),
        'solicitante_id' => \App\Models\User::factory(), // Cria um user se não for passado um
    ];
}       
}
