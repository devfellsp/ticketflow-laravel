<?php

namespace App\DTOs;

use App\Enums\TicketStatus;
use App\Enums\TicketPriority;

/**
 * DTO para atualização de Ticket
 */
class UpdateTicketDTO
{
    public function __construct(
        public readonly ?string $titulo = null,
        public readonly ?string $descricao = null,
        public readonly ?TicketStatus $status = null,
        public readonly ?TicketPriority $prioridade = null,
        public readonly ?int $responsavel_id = null,
    ) {}

    /**
     * Cria DTO a partir de array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            titulo: $data['titulo'] ?? null,
            descricao: $data['descricao'] ?? null,
            status: isset($data['status']) ? TicketStatus::from($data['status']) : null,
            prioridade: isset($data['prioridade']) ? TicketPriority::from($data['prioridade']) : null,
            responsavel_id: $data['responsavel_id'] ?? null,
        );
    }

    /**
     * Converte para array (apenas campos preenchidos)
     */
    public function toArray(): array
    {
        return array_filter([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'status' => $this->status?->value,
            'prioridade' => $this->prioridade?->value,
            'responsavel_id' => $this->responsavel_id,
        ], fn($value) => $value !== null);
    }
}