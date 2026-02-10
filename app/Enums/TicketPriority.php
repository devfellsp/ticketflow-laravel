<?php

namespace App\Enums;

enum TicketPriority: string
{
    case BAIXA = 'BAIXA';
    case MEDIA = 'MEDIA';
    case ALTA = 'ALTA';
    case CRITICA = 'CRITICA';

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
            self::BAIXA => 'Baixa',
            self::MEDIA => 'Média',
            self::ALTA => 'Alta',
            self::CRITICA => 'Crítica',
        };
    }

    /**
     * Retorna cor para badge
     */
    public function color(): string
    {
        return match($this) {
            self::BAIXA => 'gray',
            self::MEDIA => 'blue',
            self::ALTA => 'orange',
            self::CRITICA => 'red',
        };
    }

    /**
     * Retorna peso numérico (para ordenação)
     */
    public function weight(): int
    {
        return match($this) {
            self::BAIXA => 1,
            self::MEDIA => 2,
            self::ALTA => 3,
            self::CRITICA => 4,
        };
    }
}