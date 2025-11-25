<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'state_id' => 'required|integer|exists:states,id',
            'cluster_id' => 'required|integer|exists:clusters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'state_id.required' => 'Estado é obrigatório',
            'state_id.integer' => 'Estado deve ser um número',
            'state_id.exists' => 'Estado não encontrado',
            'cluster_id.required' => 'Cluster é obrigatório',
            'cluster_id.integer' => 'Cluster deve ser um número',
            'cluster_id.exists' => 'Cluster não encontrado',
        ];
    }
}
