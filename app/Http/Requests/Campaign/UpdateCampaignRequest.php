<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'active' => 'sometimes|boolean',
            'cluster_id' => 'sometimes|integer|exists:clusters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'active.boolean' => 'Active deve ser verdadeiro ou falso',
            'cluster_id.integer' => 'Cluster deve ser um número',
            'cluster_id.exists' => 'Cluster não encontrado',
        ];
    }
}
