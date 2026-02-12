<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        protected Ticket $model
    ) {}

    /**
     * Lista todos os tickets com filtros (paginado) - REQUERIDO PELA INTERFACE
     */
    public function list(array $filters = [])
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

        // Usuário comum vê apenas seus tickets
        if (Auth::check() && Auth::user()->role !== 'ADMIN') {
            $query->where(function ($q) {
                $q->where('solicitante_id', Auth::id())
                  ->orWhere('responsavel_id', Auth::id());
            });
        }
        
        return $query->latest()->paginate(15);
    }

    /**
     * Busca ticket por ID - REQUERIDO PELA INTERFACE
     */
    public function find(int $id): ?Ticket
    {
        return $this->model
            ->with(['solicitante', 'responsavel'])
            ->find($id);
    }

    /**
     * Cria novo ticket - REQUERIDO PELA INTERFACE
     */
    public function create(array $data): Ticket
    {
        return $this->model->create($data);
    }

    /**
     * Atualiza ticket - REQUERIDO PELA INTERFACE
     */
    public function update(int $id, array $data): Ticket
    {
        $ticket = $this->model->findOrFail($id);
        $ticket->update($data);
        
        return $ticket->fresh(['solicitante', 'responsavel']);
    }

    /**
     * Deleta ticket (soft delete) - REQUERIDO PELA INTERFACE
     */
    public function delete(int $id): bool
    {
        $ticket = $this->model->findOrFail($id);
        return $ticket->delete();
    }

    // ========== MÉTODOS EXTRAS (não obrigatórios pela interface) ==========

    public function all(array $filters = []): Collection
    {
        $query = $this->model->with(['solicitante', 'responsavel']);
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['prioridade'])) {
            $query->where('prioridade', $filters['prioridade']);
        }
        
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
        return $this->find($id);
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