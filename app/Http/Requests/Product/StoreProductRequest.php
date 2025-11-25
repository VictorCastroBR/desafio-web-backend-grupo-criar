<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não deve ter mais de 255 caracteres',
            'price.required' => 'Preço é obrigatório',
            'price.numeric' => 'Preço deve ser numérico',
            'price.min' => 'Preço deve ser maior ou igual a zero',
        ];
    }
}
