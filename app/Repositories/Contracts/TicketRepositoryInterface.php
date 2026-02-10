<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface do Repository de Tickets
 * Define o contrato que a implementação deve seguir
 */
interface TicketRepositoryInterface
{
    /**
     * Lista todos os tickets com paginação e filtros
     */
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Busca ticket por ID (com relacionamentos)
     */
    public function findById(int $id): ?Ticket;

    /**
     * Cria novo ticket
     */
    public function create(array $data): Ticket;

    /**
     * Atualiza ticket
     */
    public function update(int $id, array $data): Ticket;

    /**
     * Deleta ticket (soft delete)
     */
    public function delete(int $id): bool;

    /**
     * Busca tickets por status
     */
    public function findByStatus(TicketStatus $status): Collection;

    /**
     * Busca tickets por prioridade
     */
    public function findByPriority(TicketPriority $priority): Collection;

    /**
     * Busca tickets de um solicitante
     */
    public function findBySolicitante(int $userId): Collection;

    /**
     * Busca tickets de um responsável
     */
    public function findByResponsavel(int $userId): Collection;

    /**
     * Conta tickets por status
     */
    public function countByStatus(): array;
}