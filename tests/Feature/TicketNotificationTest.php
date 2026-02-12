<?php

use App\Models\User;
use App\Models\Ticket;
use App\Notifications\TicketResolvidoNotification;
use Illuminate\Support\Facades\Notification;

test('notificacao e enviada quando ticket e resolvido', function () {
    Notification::fake();
    
    $solicitante = User::factory()->create(['role' => 'USER']);
    $responsavel = User::factory()->create(['role' => 'TECNICO']);
    
    $ticket = Ticket::factory()->create([
        'solicitante_id' => $solicitante->id,
        'responsavel_id' => $responsavel->id,
        'status' => 'ABERTO',
    ]);
    
    // Mudar status para RESOLVIDO (como responsável)
    $this->actingAs($responsavel, 'sanctum')
         ->patchJson("/api/tickets/{$ticket->id}/status", [
             'status' => 'RESOLVIDO'
         ])
         ->assertStatus(200);
    
    // Verificar que a notificação foi enviada
    Notification::assertSentTo(
        $solicitante,
        TicketResolvidoNotification::class,
        function ($notification) use ($ticket) {
            return $notification->ticket->id === $ticket->id;
        }
    );
});

test('notificacao nao e enviada quando status nao e resolvido', function () {
    Notification::fake();
    
    $solicitante = User::factory()->create(['role' => 'USER']);
    $responsavel = User::factory()->create(['role' => 'TECNICO']);
    
    $ticket = Ticket::factory()->create([
        'solicitante_id' => $solicitante->id,
        'responsavel_id' => $responsavel->id,
        'status' => 'ABERTO',
    ]);
    
    // Mudar status para EM_ANDAMENTO (não deve notificar)
    $this->actingAs($responsavel, 'sanctum')
         ->patchJson("/api/tickets/{$ticket->id}/status", [
             'status' => 'EM_ANDAMENTO'
         ])
         ->assertStatus(200);
    
    // Verificar que NENHUMA notificação foi enviada
    Notification::assertNothingSent();
});