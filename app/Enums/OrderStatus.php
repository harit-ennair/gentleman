<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Processing => 'En traitement',
            self::Completed => 'Terminé',
            self::Cancelled => 'Annulé',
            self::Refunded => 'Remboursé',
        };
    }
}
