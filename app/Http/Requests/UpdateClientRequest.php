<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $mobilePhonePattern = "#^0(10|11|12|15)[0-9]{8}$#";
        return [
            'name'=>['sometimes', 'string', 'max:255'],
            'phone'=>['sometimes', 'string', 'unique:clients,phone', 'regex:' . $mobilePhonePattern],
            'line_phone'=>['sometimes', 'string', 'numeric', 'unique:clients,line_phone','max_digits:9'],
            'address'=>['sometimes', 'string', 'max:400'],
            'credit_allowed'=>['sometimes','boolean'],
            'credit_limit'=>['sometimes','numeric', 'min:0'],
        ];
    }
}
