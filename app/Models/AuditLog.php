<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'user_id',
        'action',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relação polimórfica com o modelo auditado
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Usuário que fez a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}