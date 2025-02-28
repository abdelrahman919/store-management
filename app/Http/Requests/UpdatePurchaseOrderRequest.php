<?php

namespace App\Http\Requests;

use App\Helpers\ValidationRulesHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
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
            'data'=> ['sometimes', 'required', 'array', 'min:1'],
            'data.order_code' => ['sometimes', 'required', 'string'],
            'relations'=>['sometimes', 'required', 'min:1'],
            'relations.supplier_id' => ['sometimes', 'required', 'integer', 'exists:suppliers,id'],
        
        
            'relations.entries' => ['sometimes', 'required', 'array', 'min:1'],
            'relations.entries.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'relations.entries.*.quantity' => ['required', 'numeric', 'min:1'],
            'relations.entries.*.cost_price' => ['required', 'numeric', 'min:0'],
            'relations.entries.*.total_price' => ['required', 'numeric', 'min:0'],
        
        
        ];
    }
}
