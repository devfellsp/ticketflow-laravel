<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste 1: Usuário não autenticado não pode acessar listagem de tickets
     */
    public function test_unauthenticated_user_cannot_access_tickets(): void
    {
        $response = $this->getJson('/api/tickets');
        
        $response->assertStatus(401);
    }

    /**
     * Teste 2: PATCH de status cria log e atualiza resolved_at quando RESOLVIDO
     */
    public function test_patch_status_creates_log_and_updates_resolved_at(): void
    {
        // Criar usuário e ticket
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $user->id,
            'status' => 'ABERTO',
            'resolved_at' => null,
        ]);

        // Autenticar
        $this->actingAs($user);

        // Mudar status para RESOLVIDO
        $response = $this->patchJson("/api/tickets/{$ticket->id}/status", [
            'status' => 'RESOLVIDO'
        ]);

        $response->assertStatus(200);

        // Verificar que resolved_at foi preenchido
        $ticket->refresh();
        $this->assertNotNull($ticket->resolved_at);
        $this->assertEquals('RESOLVIDO', $ticket->status->value);

        // Verificar que log foi criado
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $ticket->id,
            'auditable_type' => Ticket::class,
            'action' => 'updated',
            'user_id' => $user->id,
        ]);

        // Verificar descrição do log contém mudança de status
        $log = AuditLog::where('auditable_id', $ticket->id)
            ->where('action', 'updated')
            ->latest()
            ->first();
        
        $this->assertStringContainsString('ABERTO', $log->description);
        $this->assertStringContainsString('RESOLVIDO', $log->description);
    }

    /**
     * Teste 3: Apenas solicitante ou admin pode deletar ticket
     */
    public function test_only_owner_or_admin_can_delete_ticket(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $owner->id,
        ]);

        // Tentar deletar como outro usuário (NÃO admin)
        $this->actingAs($otherUser);
        $response = $this->deleteJson("/api/tickets/{$ticket->id}");
        $response->assertStatus(403);

        // Deletar como dono
        $this->actingAs($owner);
        $response = $this->deleteJson("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);
    }
}