<?php

namespace App\Enums;

enum TicketPriority: string
{
    case BAIXA = 'BAIXA';
    case MEDIA = 'MEDIA';
    case ALTA = 'ALTA';

    /**
     * Label humanizado
     */
    public function label(): string
    {
        return match($this) {
            self::BAIXA => 'Baixa',
            self::MEDIA => 'Média',
            self::ALTA => 'Alta',
        };
    }

    /**
     * Cor para UI
     */
    public function color(): string
    {
        return match($this) {
            self::BAIXA => 'green',
            self::MEDIA => 'yellow',
            self::ALTA => 'red',
        };
    }

    /**
     * Peso para ordenação (maior = mais urgente)
     */
    public function weight(): int
    {
        return match($this) {
            self::BAIXA => 1,
            self::MEDIA => 2,
            self::ALTA => 3,
        };
    }
}