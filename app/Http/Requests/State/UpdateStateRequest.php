<?php

namespace App\Http\Requests\State;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'uf' => 'sometimes|string|size:2',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'uf.string' => 'UF deve ser texto',
            'uf.size' => 'UF deve ter exatamente 2 caracteres',
        ];
    }
}
