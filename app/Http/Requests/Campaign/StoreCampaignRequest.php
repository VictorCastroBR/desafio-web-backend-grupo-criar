<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
            'cluster_id' => 'required|integer|exists:clusters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'active.boolean' => 'Active deve ser verdadeiro ou falso',
            'cluster_id.required' => 'Cluster é obrigatório',
            'cluster_id.integer' => 'Cluster deve ser um número',
            'cluster_id.exists' => 'Cluster não encontrado',
        ];
    }
}
