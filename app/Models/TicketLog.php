<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLog extends Model
{
    const UPDATED_AT = null; // Não precisa de updated_at

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'before',
        'after',
        'description',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Ticket relacionado
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Usuário que fez a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}