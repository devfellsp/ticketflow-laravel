<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'solicitante_id',
        'responsavel_id',
        'resolved_at',
    ];

    /**
     * Casting de atributos (converte automaticamente para Enum)
     */
    protected $casts = [
        'status' => TicketStatus::class,
        'prioridade' => TicketPriority::class,
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento: Ticket pertence a um Solicitante (User)
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /**
     * Relacionamento: Ticket pertence a um Responsável (User)
     */
    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    /**
     * Accessor: Label do status (usando Enum)
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? $this->attributes['status'];
    }

    /**
     * Accessor: Cor do status (usando Enum)
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status?->color() ?? 'gray';
    }

    /**
     * Accessor: Label da prioridade (usando Enum)
     */
    public function getPrioridadeLabelAttribute(): string
    {
        return $this->prioridade?->label() ?? $this->attributes['prioridade'];
    }

    /**
     * Accessor: Cor da prioridade (usando Enum)
     */
    public function getPrioridadeColorAttribute(): string
    {
        return $this->prioridade?->color() ?? 'gray';
    }

    /**
     * Accessor: Peso da prioridade (para ordenação)
     */
    public function getPrioridadeWeightAttribute(): int
    {
        return $this->prioridade?->weight() ?? 0;
    }

    /**
     * Scope: Filtra por status
     */
    public function scopeStatus($query, TicketStatus $status)
    {
        return $query->where('status', $status->value);
    }

    /**
     * Scope: Filtra por prioridade
     */
    public function scopePrioridade($query, TicketPriority $prioridade)
    {
        return $query->where('prioridade', $prioridade->value);
    }

    /**
     * Scope: Tickets abertos
     */
    public function scopeAbertos($query)
    {
        return $query->where('status', TicketStatus::ABERTO->value);
    }

    /**
     * Scope: Tickets em andamento
     */
    public function scopeEmAndamento($query)
    {
        return $query->where('status', TicketStatus::EM_ANDAMENTO->value);
    }

    /**
     * Scope: Tickets resolvidos
     */
    public function scopeResolvidos($query)
    {
        return $query->where('status', TicketStatus::RESOLVIDO->value);
    }
    /**
 * Logs de auditoria
 */
public function logs(): HasMany
{
    return $this->hasMany(TicketLog::class)->orderBy('created_at', 'desc');
}
}