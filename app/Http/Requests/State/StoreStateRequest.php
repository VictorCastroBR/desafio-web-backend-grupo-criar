<?php

namespace App\Http\Requests\State;

use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'uf.required' => 'UF é obrigatória',
            'uf.string' => 'UF deve ser texto',
            'uf.size' => 'UF deve ter exatamente 2 caracteres',
        ];
    }
}
