<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ValidationRulesHelper;

class StoreSupplierRequest extends FormRequest
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
            'name'=>['required', 'string', 'max:255'],
            'phone'=>array_merge(['required', 'unique:suppliers,phone'], ValidationRulesHelper::validPhone()),
            'line_phone'=>array_merge(['unique:clients,line_phone'],ValidationRulesHelper::validLinePhone())

        ];
    }
}
