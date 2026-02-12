<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\AuditLog;
use App\Enums\TicketStatus;
use App\Notifications\TicketResolvidoNotification;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $repository
    ) {}

    public function list(array $filters = [])
    {
        return $this->repository->list($filters);
    }

    public function create(array $data): Ticket
    {
        return $this->repository->create($data);
    }

    public function update(int $ticketId, array $data): Ticket
    {
        return $this->repository->update($ticketId, $data);
    }

    public function delete(int $ticketId): bool
    {
        return $this->repository->delete($ticketId);
    }

    public function find(int $ticketId): ?Ticket
    {
        return $this->repository->find($ticketId);
    }

    public function changeStatus(int $ticketId, TicketStatus $status, int $userId): Ticket
    {
        $ticket = $this->repository->find($ticketId);
        $oldStatus = $ticket->status->value;
        
        $data = ['status' => $status->value];
        
        if ($status === TicketStatus::RESOLVIDO) {
            $data['resolved_at'] = now();
        }
        
        $ticket = $this->repository->update($ticketId, $data);
        
        // Criar log com user_id obrigatório
        AuditLog::create([
            'auditable_type' => Ticket::class,
            'auditable_id' => $ticket->id,
            'user_id' => $userId,
            'action' => 'updated',
            'description' => "Ticket #{$ticket->id} atualizado: status: '{$oldStatus}' → '{$status->value}'",
            'changes' => [
                'status' => [
                    'old' => $oldStatus,
                    'new' => $status->value,
                ],
            ],
        ]);
        
        // 🎁 BÔNUS: Disparar notificação quando RESOLVIDO
        if ($status === TicketStatus::RESOLVIDO) {
            $ticket->solicitante->notify(new TicketResolvidoNotification($ticket));
        }
        
        return $ticket->fresh();
    }

    public function assignResponsible(int $ticketId, ?int $responsibleId, int $userId): Ticket
    {
        $ticket = $this->repository->find($ticketId);
        $oldResponsibleId = $ticket->responsavel_id;
        
        $ticket = $this->repository->update($ticketId, [
            'responsavel_id' => $responsibleId
        ]);
        
        AuditLog::create([
            'auditable_type' => Ticket::class,
            'auditable_id' => $ticket->id,
            'user_id' => $userId,
            'action' => 'updated',
            'description' => "Ticket #{$ticket->id} atualizado: responsavel_id: '{$oldResponsibleId}' → '{$responsibleId}'",
            'changes' => [
                'responsavel_id' => [
                    'old' => $oldResponsibleId,
                    'new' => $responsibleId,
                ],
            ],
        ]);
        
        return $ticket->fresh();
    }

    public function getLogs(int $ticketId)
    {
        return AuditLog::where('auditable_type', Ticket::class)
            ->where('auditable_id', $ticketId)
            ->with('user:id,name,email')
            ->latest()
            ->get();
    }

    public function dashboard(): array
    {
        $counts = Ticket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'status_counts' => $counts,
            'total' => array_sum($counts),
        ];
    }
}
