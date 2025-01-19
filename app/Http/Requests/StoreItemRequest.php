<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'data' => [
                'name' => ['required', 'string', 'max:255'],
                'selling_price' => ['required', 'numeric', 'min:0'],
                'cost_price' => ['required', 'numeric', 'min:0'],
                'package_size' => ['required', 'numeric', 'min:1'],
                'stock' => ['sometimes', 'numeric', 'min:0']
            ],
            'relations.codes' => ['sometimes', 'array', 'min:1'],
            'relations.codes.*' => ['required_with:relations.*.codes', 'string', 'distinct', 'unique:codes,value'],
        ];
    }

    public function messages()
    {
        return [
            'relations.codes.min' => 'At least one code is required',
            'relations.codes.*.string' => 'Each code must be a string',
            'relations.codes.*.distinct' => 'Same code entered multiple times',
            'relations.codes.*.unique' => 'Code is already taken, Codes must be unique',
        ];
    }
}
