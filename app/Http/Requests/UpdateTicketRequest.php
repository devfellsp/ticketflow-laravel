<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorização via Policy
    }

    public function rules(): array
    {
        return [
            'titulo' => ['sometimes', 'string', 'min:5', 'max:120'],
            'descricao' => ['sometimes', 'string', 'min:20'],
            'status' => ['sometimes', 'in:ABERTO,EM_ANDAMENTO,RESOLVIDO'],
            'prioridade' => ['sometimes', 'in:BAIXA,MEDIA,ALTA'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.min' => 'O título deve ter no mínimo 5 caracteres.',
            'titulo.max' => 'O título deve ter no máximo 120 caracteres.',
            'descricao.min' => 'A descrição deve ter no mínimo 20 caracteres.',
            'status.in' => 'O status deve ser ABERTO, EM_ANDAMENTO ou RESOLVIDO.',
            'prioridade.in' => 'A prioridade deve ser BAIXA, MEDIA ou ALTA.',
            'responsavel_id.exists' => 'O responsável selecionado não existe.',
        ];
    }
}