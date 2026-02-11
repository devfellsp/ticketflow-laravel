<?php

namespace App\Services;

use App\DTOs\CreateTicketDTO;
use App\DTOs\UpdateTicketDTO;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service de Tickets
 * Contém toda a lógica de negócio
 */
class TicketService
{
    public function __construct(
        protected TicketRepositoryInterface $repository
    ) {}

    /**
     * Lista todos os tickets com filtros
     */
    
    /**
     * Busca ticket por ID
     */
    public function getTicketById(int $id): ?Ticket
    {
        return $this->repository->findById($id);
    }

    /**
     * Cria novo ticket
     */
    public function createTicket(CreateTicketDTO $dto): Ticket
    {
        DB::beginTransaction();
        
        try {
            $data = $dto->toArray();
            
            // Define status inicial
            $data['status'] = TicketStatus::ABERTO->value;
            
            $ticket = $this->repository->create($data);
            
            // TODO: Disparar evento TicketCreated
            // event(new TicketCreated($ticket));
            
            DB::commit();
            
            return $ticket;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Atualiza ticket
     */
    public function updateTicket(int $id, UpdateTicketDTO $dto): Ticket
    {
        DB::beginTransaction();
        
        try {
            $ticket = $this->repository->findById($id);
            
            if (!$ticket) {
                throw new Exception("Ticket #{$id} não encontrado");
            }
            
            $data = $dto->toArray();
            
            // Lógica de negócio: Se status mudou para RESOLVIDO, marca data de resolução
            if (isset($data['status']) && $data['status'] === TicketStatus::RESOLVIDO->value) {
                if ($ticket->status !== TicketStatus::RESOLVIDO->value) {
                    $data['resolved_at'] = now();
                }
            }
            
            $updatedTicket = $this->repository->update($id, $data);
            
            // TODO: Disparar evento TicketUpdated
            // event(new TicketUpdated($ticket, $updatedTicket));
            
            DB::commit();
            
            return $updatedTicket;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Deleta ticket (soft delete)
     */
    public function deleteTicket(int $id): bool
    {
        DB::beginTransaction();
        
        try {
            $ticket = $this->repository->findById($id);
            
            if (!$ticket) {
                throw new Exception("Ticket #{$id} não encontrado");
            }
            
            $result = $this->repository->delete($id);
            
            // TODO: Disparar evento TicketDeleted
            // event(new TicketDeleted($ticket));
            
            DB::commit();
            
            return $result;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Atribui responsável ao ticket
     */
    public function assignResponsible(int $ticketId, int $userId): Ticket
    {
        $ticket = $this->repository->findById($ticketId);
        
        if (!$ticket) {
            throw new Exception("Ticket #{$ticketId} não encontrado");
        }
        
        // Muda status para EM_ANDAMENTO se estava ABERTO
        $data = ['responsavel_id' => $userId];
        
        if ($ticket->status === TicketStatus::ABERTO->value) {
            $data['status'] = TicketStatus::EM_ANDAMENTO->value;
        }
        
        return $this->repository->update($ticketId, $data);
    }

    /**
     * Muda status do ticket
     */
    public function changeStatus(int $ticketId, TicketStatus $status): Ticket
    {
        $data = ['status' => $status->value];
        
        // Se está resolvendo, marca data
        if ($status === TicketStatus::RESOLVIDO) {
            $data['resolved_at'] = now();
        }
        
        return $this->repository->update($ticketId, $data);
    }

    /**
     * Busca tickets por status
     */
    public function getTicketsByStatus(TicketStatus $status): Collection
    {
        return $this->repository->findByStatus($status);
    }

    /**
     * Busca tickets por prioridade
     */
    public function getTicketsByPriority(TicketPriority $priority): Collection
    {
        return $this->repository->findByPriority($priority);
    }

    /**
     * Busca tickets do solicitante
     */
    public function getTicketsBySolicitante(int $userId): Collection
    {
        return $this->repository->findBySolicitante($userId);
    }

    /**
     * Busca tickets do responsável
     */
    public function getTicketsByResponsavel(int $userId): Collection
    {
        return $this->repository->findByResponsavel($userId);
    }

    /**
     * Dashboard - Contagem por status
     */
    public function getStatusCounts(): array
    {
        return $this->repository->countByStatus();
    }
    
/**
 * Lista todos os tickets com filtros
 */
public function listTickets(array $filters = []): Collection
{
    return $this->repository->all($filters);
}

}