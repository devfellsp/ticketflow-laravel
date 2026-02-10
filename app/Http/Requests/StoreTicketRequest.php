<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Como @RolesAllowed no Quarkus
     */
    public function authorize(): bool
    {
        // Apenas usuários autenticados podem criar tickets
        return true;
    
    }

    /**
     * Get the validation rules that apply to the request.
     * Equivalente ao Bean Validation (@NotNull, @Size, etc) no Java
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => [
                'required',           // @NotNull
                'string',
                'min:5',             // @Size(min=5)
                'max:120',           // @Size(max=120)
            ],
            'descricao' => [
                'required',
                'string',
                'min:20',            // Mínimo 20 caracteres
            ],
            'prioridade' => [
                'required',
                'in:BAIXA,MEDIA,ALTA,CRITICA', // ENUM
            ],
            'responsavel_id' => [
                'nullable',
                'exists:users,id',   // Verifica se existe na tabela users
            ],
        ];
    }

    /**
     * Mensagens de erro customizadas (opcional)
     * Melhora a experiência do usuário
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.min' => 'O título deve ter no mínimo 5 caracteres.',
            'titulo.max' => 'O título não pode ter mais de 120 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter no mínimo 20 caracteres.',
            'prioridade.required' => 'Selecione uma prioridade.',
            'prioridade.in' => 'Prioridade inválida. Use: BAIXA, MEDIA, ALTA ou CRITICA.',
            'responsavel_id.exists' => 'O responsável selecionado não existe.',
        ];
    }
}