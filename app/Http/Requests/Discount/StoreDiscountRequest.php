<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'nullable|numeric|min:0',
            'percent' => 'nullable|numeric|min:0|max:100',
            'campaign_id' => 'required|integer|exists:campaigns,id'
        ];
    }

    public function messages(): array
    {
        return [
            'campaign_id.required' => 'Campanha é obrigatória',
            'campaign_id.exists' => 'Campanha não encontrada',
            'value.numeric' => 'Valor deve ser numérico',
            'percent.numeric' => 'Percentual deve ser numérico',
            'percent.max' => 'Percentual não deve ser maior do que 100'
        ];
    }
}
