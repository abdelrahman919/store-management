<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: BASE STORE REQUEST TO ENFORCE SHIFT EXISTENCE 
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
            'data' => ['array', 'required', 'min:1'],
            'data.payment_method' => ['required', new Enum(PaymentMethod::class)],
            'data.order_code' => ['required', 'string'],
            'data.total_price' => ['required', 'numeric', $this->total_price()],

            'relations.entries' => ['required', 'array', 'min:1'],
            'relations.entries.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'relations.entries.*.quantity' => ['required', 'numeric', 'min:1'],
            'relations.entries.*.cost_price' => ['required', 'numeric', 'min:0'],
            'relations.entries.*.total_price' => ['required', 'numeric', 'min:0'],
            'relations.supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
        ];
    }


    private function total_price(){
        return function ($attribute, $value, $fail) {
            $entries = collect(request()->input('relations.entries'));
            $calculatedTotal = $entries->map(fn($entry)=>$entry['total_price'])->sum();
            if ($calculatedTotal !== $value) {
                $fail("Incorrect total price! The correct price is $calculatedTotal, but $value was given.");
            }
        };
    }


}

