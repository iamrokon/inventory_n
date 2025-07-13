<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // set to false if you want to restrict access
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
}
