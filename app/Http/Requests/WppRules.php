<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WppRules extends FormRequest
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
            'name' => 'required|string|max:255', // O nome é obrigatório, deve ser uma string e ter no máximo 255 caracteres.
            'phone' => 'required|numeric|digits:11', // O telefone é obrigatório, deve ser um número e ter 11 dígitos.
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório.',
            'phone.required' => 'Número do Whatsapp é obrigatório.',
            'phone.digits' => 'Número do Whatsapp tem que ser um número de telefone válido',
            // Mensagens personalizadas para os campos
        ];
    }
}
