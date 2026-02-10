<?php

namespace App\Enums;

enum TicketStatus: string
{
    case ABERTO = 'ABERTO';
    case EM_ANDAMENTO = 'EM_ANDAMENTO';
    case RESOLVIDO = 'RESOLVIDO';

    /**
     * Retorna todos os valores possíveis
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retorna label amigável
     */
    public function label(): string
    {
        return match($this) {
            self::ABERTO => 'Aberto',
            self::EM_ANDAMENTO => 'Em Andamento',
            self::RESOLVIDO => 'Resolvido',
        };
    }

    /**
     * Retorna cor para badge (frontend)
     */
    public function color(): string
    {
        return match($this) {
            self::ABERTO => 'blue',
            self::EM_ANDAMENTO => 'yellow',
            self::RESOLVIDO => 'green',
        };
    }
}