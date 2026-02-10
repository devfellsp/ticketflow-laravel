<?php

namespace App\DTOs;

use App\Enums\TicketPriority;

/**
 * DTO para criação de Ticket
 * Similar ao padrão usado em Java/Quarkus
 */
class CreateTicketDTO
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $descricao,
        public readonly TicketPriority $prioridade,
        public readonly int $solicitante_id,
        public readonly ?int $responsavel_id = null,
    ) {}

    /**
     * Cria DTO a partir de array (vindo do Request)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            titulo: $data['titulo'],
            descricao: $data['descricao'],
            prioridade: TicketPriority::from($data['prioridade']),
            solicitante_id: $data['solicitante_id'],
            responsavel_id: $data['responsavel_id'] ?? null,
        );
    }

    /**
     * Converte para array (para salvar no banco)
     */
    public function toArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'prioridade' => $this->prioridade->value,
            'solicitante_id' => $this->solicitante_id,
            'responsavel_id' => $this->responsavel_id,
        ];
    }
}