<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        protected Ticket $model
    ) {}

    /**
     * Lista todos os tickets com filtros opcionais
     */
    public function all(array $filters = []): Collection
    {
        $query = $this->model->with(['solicitante', 'responsavel']);
        
        // Filtro por status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Filtro por prioridade
        if (!empty($filters['prioridade'])) {
            $query->where('prioridade', $filters['prioridade']);
        }
        
        // Busca por texto (titulo ou descricao)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('descricao', 'LIKE', "%{$search}%");
            });
        }
        
        return $query->latest()->get();
    }

    public function findById(int $id): ?Ticket
    {
        return $this->model
            ->with(['solicitante', 'responsavel'])
            ->find($id);
    }

    public function create(array $data): Ticket
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Ticket
    {
        $ticket = $this->model->findOrFail($id);
        $ticket->update($data);
        
        // Retorna o ticket atualizado com relacionamentos
        return $ticket->fresh(['solicitante', 'responsavel']);
    }

    public function delete(int $id): bool
    {
        $ticket = $this->model->findOrFail($id);
        
        // Soft delete
        return $ticket->delete();
    }

    public function findByStatus(TicketStatus $status): Collection
    {
        return $this->model
            ->where('status', $status->value)
            ->with(['solicitante', 'responsavel'])
            ->get();
    }

    public function findByPriority(TicketPriority $priority): Collection
    {
        return $this->model
            ->where('prioridade', $priority->value)
            ->with(['solicitante', 'responsavel'])
            ->get();
    }

    public function findBySolicitante(int $userId): Collection
    {
        return $this->model
            ->where('solicitante_id', $userId)
            ->with(['responsavel'])
            ->get();
    }

    public function findByResponsavel(int $userId): Collection
    {
        return $this->model
            ->where('responsavel_id', $userId)
            ->with(['solicitante'])
            ->get();
    }

    public function countByStatus(): array
    {
        return $this->model
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }
}