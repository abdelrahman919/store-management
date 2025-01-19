<?php

namespace App\Http\Requests;

use App\Helpers\ValidationRulesHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: auth
        return true;
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
            'name'=>['required', 'string', 'max:255'],
            'phone'=>array_merge(['required', 'unique:clients,phone'],ValidationRulesHelper::validPhone()),
            'line_phone'=>array_merge(['unique:clients,line_phone'],ValidationRulesHelper::validLinePhone()),
            'address'=>['required', 'string', 'max:400'],
            'credit_allowed'=>['sometimes', 'boolean'],
            'credit_limit'=>['sometimes', 'required', 'numeric', 'min:0'],
        ];
    }
}
