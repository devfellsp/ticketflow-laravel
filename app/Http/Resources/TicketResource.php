<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para formatar resposta de Ticket na API
 * Controla quais campos são retornados e como são formatados
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'status' => [
                'value' => $this->status,
                'label' => $this->status_label ?? $this->status,
                'color' => $this->status_color ?? 'gray',
            ],
            'prioridade' => [
                'value' => $this->prioridade,
                'label' => $this->prioridade_label ?? $this->prioridade,
                'color' => $this->prioridade_color ?? 'gray',
            ],
            
            // Relacionamentos
            'solicitante' => [
                'id' => $this->solicitante_id,
                'name' => $this->solicitante?->name,
                'email' => $this->solicitante?->email,
            ],
            
            'responsavel' => $this->when($this->responsavel_id, [
                'id' => $this->responsavel_id,
                'name' => $this->responsavel?->name,
                'email' => $this->responsavel?->email,
            ]),
            
            // Datas
            'resolved_at' => $this->resolved_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Informações adicionais (apenas se solicitado)
            'deleted_at' => $this->when($request->user()?->role === 'admin', 
                $this->deleted_at?->format('Y-m-d H:i:s')
            ),
        ];
    }

    /**
     * Informações adicionais na resposta
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
            ],
        ];
    }
}