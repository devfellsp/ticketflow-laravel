<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Policy de Autorização para Tickets
 */
class TicketPolicy
{
    /**
     * Ver qualquer ticket (apenas autenticados)
     */
    public function viewAny(User $user): bool
    {
        return true; // Qualquer usuário autenticado pode listar
    }

    /**
     * Ver um ticket específico
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return true; // Qualquer usuário autenticado pode ver
    }

    /**
     * Criar ticket (qualquer usuário autenticado)
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Atualizar ticket
     * Apenas: solicitante, responsável ou admin
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin'
            || $ticket->solicitante_id === $user->id
            || $ticket->responsavel_id === $user->id;
    }

    /**
     * Excluir ticket
     * Apenas: solicitante ou admin (REQUISITO OBRIGATÓRIO)
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin'
            || $ticket->solicitante_id === $user->id;
    }

    /**
     * Restaurar ticket (soft delete)
     * Apenas admin
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Forçar exclusão permanente
     * Apenas admin
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin';
    }
}
