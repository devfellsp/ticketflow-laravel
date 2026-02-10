<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        protected Ticket $model
    ) {}

    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['solicitante', 'responsavel']);

        // Filtros opcionais
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['prioridade'])) {
            $query->where('prioridade', $filters['prioridade']);
        }

        if (isset($filters['solicitante_id'])) {
            $query->where('solicitante_id', $filters['solicitante_id']);
        }

        if (isset($filters['responsavel_id'])) {
            $query->where('responsavel_id', $filters['responsavel_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('titulo', 'like', "%{$filters['search']}%")
                  ->orWhere('descricao', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
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