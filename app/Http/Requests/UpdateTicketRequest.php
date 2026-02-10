<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autorização será feita pela Policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => [
                'sometimes',         // Apenas se enviado (PATCH pode atualizar parcialmente)
                'string',
                'min:5',
                'max:120',
            ],
            'descricao' => [
                'sometimes',
                'string',
                'min:20',
            ],
            'status' => [
                'sometimes',
                'in:ABERTO,EM_ANDAMENTO,RESOLVIDO,FECHADO', // ENUM status
            ],
            'prioridade' => [
                'sometimes',
                'in:BAIXA,MEDIA,ALTA,CRITICA',
            ],
            'responsavel_id' => [
                'nullable',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Mensagens customizadas
     */
    public function messages(): array
    {
        return [
            'titulo.min' => 'O título deve ter no mínimo 5 caracteres.',
            'titulo.max' => 'O título não pode ter mais de 120 caracteres.',
            'descricao.min' => 'A descrição deve ter no mínimo 20 caracteres.',
            'status.in' => 'Status inválido. Use: ABERTO, EM_ANDAMENTO, RESOLVIDO ou FECHADO.',
            'prioridade.in' => 'Prioridade inválida.',
            'responsavel_id.exists' => 'O responsável selecionado não existe.',
        ];
    }
}