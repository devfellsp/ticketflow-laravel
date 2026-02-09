<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importante para o Soft Delete

class Ticket extends Model
{
    use HasFactory, SoftDeletes; // Ativa a exclusão lógica

    // Campos que podem ser preenchidos em massa (Mass Assignment)
    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'solicitante_id',
        'responsavel_id',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Relacionamento: O usuário que criou o chamado.
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /**
     * Relacionamento: O usuário responsável por resolver.
     */
    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }   
}       