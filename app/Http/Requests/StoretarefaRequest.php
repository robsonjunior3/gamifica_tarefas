<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoretarefaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'pontuacao' => 'required|integer',
            'criador_id' => 'required|exists:usuarios,id',
            'responsavel_id' => 'nullable|exists:usuarios,id',
            'concluida' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.string' => 'O campo descrição deve ser uma string.',
            'pontuacao.required' => 'O campo pontuação é obrigatório.',
            'pontuacao.integer' => 'O campo pontuação deve ser um número inteiro.',
            'criador_id.required' => 'O campo criador é obrigatório.',
            'criador_id.exists' => 'O criador selecionado não é válido.',
            'responsavel_id.exists' => 'O responsável selecionado não é válido.',
            'concluida.boolean' => 'O campo concluída deve ser verdadeiro ou falso.',
        ];
    }
}
