<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Auth;

class TicketObserver
{
    /**
     * Quando um ticket é CRIADO
     */
    public function created(Ticket $ticket): void
    {
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'after' => $this->normalizeData($ticket->toArray()),
            'description' => "Ticket #{$ticket->id} criado",
        ]);
    }

    /**
     * Quando um ticket é ATUALIZADO
     */
    public function updated(Ticket $ticket): void
    {
        $changes = $ticket->getChanges();
        $original = $ticket->getOriginal();

        // Remove campos de controle
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        // Monta descrição das mudanças
        $description = "Ticket #{$ticket->id} atualizado: ";
        $changesDescription = [];

        foreach ($changes as $field => $newValue) {
            $oldValue = $original[$field] ?? null;
            
            // Converte valores para string legível
            $oldStr = $this->valueToString($oldValue);
            $newStr = $this->valueToString($newValue);
            
            $changesDescription[] = "$field: '$oldStr' → '$newStr'";
        }

        $description .= implode(', ', $changesDescription);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'before' => $this->normalizeData(array_intersect_key($original, $changes)),
            'after' => $this->normalizeData($changes),
            'description' => $description,
        ]);
    }

    /**
     * Quando um ticket é DELETADO
     */
    public function deleted(Ticket $ticket): void
    {
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'before' => $this->normalizeData($ticket->toArray()),
            'description' => "Ticket #{$ticket->id} deletado",
        ]);
    }

    /**
     * Normaliza dados convertendo Enums para strings
     */
    private function normalizeData(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($value instanceof \BackedEnum) {
                $data[$key] = $value->value;
            }
        }
        return $data;
    }

    /**
     * Converte valor para string legível
     */
    private function valueToString($value): string
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }
        if (is_null($value)) {
            return 'null';
        }
        return (string) $value;
    }
}