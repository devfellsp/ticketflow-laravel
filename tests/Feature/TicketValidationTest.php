<?php

use App\Models\User;

test('titulo deve ter entre 5 e 120 caracteres', function () {
    $user = User::factory()->create(['role' => 'ADMIN']);
    
    // Teste com titulo muito curto (4 caracteres)
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tickets', [
        'titulo' => 'Test',
        'descricao' => 'Descrição com mais de 20 caracteres necessários',
        'prioridade' => 'ALTA',
    ]);
    
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['titulo']);
    
    // Teste com titulo muito longo (121 caracteres)
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tickets', [
        'titulo' => str_repeat('a', 121),
        'descricao' => 'Descrição com mais de 20 caracteres necessários',
        'prioridade' => 'ALTA',
    ]);
    
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['titulo']);
    
    // Teste com titulo válido (5-120 caracteres)
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tickets', [
        'titulo' => 'Título válido',
        'descricao' => 'Descrição com mais de 20 caracteres necessários',
        'prioridade' => 'ALTA',
    ]);
    
    $response->assertStatus(201);
});

test('descricao deve ter no minimo 20 caracteres', function () {
    $user = User::factory()->create(['role' => 'ADMIN']);
    
    // Teste com descrição muito curta (19 caracteres)
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tickets', [
        'titulo' => 'Titulo valido',
        'descricao' => 'Menos de 20 chars',
        'prioridade' => 'ALTA',
    ]);
    
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['descricao']);
    
    // Teste com descrição válida (20+ caracteres)
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tickets', [
        'titulo' => 'Titulo valido',
        'descricao' => 'Esta descrição tem exatamente vinte ou mais caracteres',
        'prioridade' => 'ALTA',
    ]);
    
    $response->assertStatus(201);
});