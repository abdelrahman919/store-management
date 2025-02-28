<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Factories\ModelFactories\PersistableTransactionFactory;
use App\Helpers\ValidationRulesHelper;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class StoreTransactionRequest extends FormRequest
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
        $validTypes = implode(',', PersistableTransactionFactory::getTypes());
        $itemRequiredTypes = implode(',', PersistableTransactionFactory::getItemRequiredTypes());
        
        return [
            
            'data' => ValidationRulesHelper::requiredArray(),
            'data.type' => ['string', 'in:' . $validTypes],
            'data.owner_id' => ['required'],
            'data.owner_type' => ['required', 'in:client,patient'],
            'data.payment_method' => ['required', new Enum(PaymentMethod::class), $this->validPaymentMethodForType()],
            'data.original_price' => ['required', 'numeric', 'min:0', $this->original_price()],
            'data.discount' => ['sometimes', 'numeric', 'min:0', 'lte:data.original_price'],
            'data.final_price' => ['required', 'numeric', 'min:0', 'lte:data.original_price', $this->final_price()],
            
            'relations.items' => ['required_if:data.type,' . $itemRequiredTypes, 'array', 'min:1'],
            'relations.items.*.item_id' => ['required', 'exists:items,id'],
            'relations.items.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }


    private function final_price()
    {
        return function ($attribute, $value, $fail) {
            $discount = request()->input('data.discount') ?? 0;
            $expected = request()->input('data.original_price') - $discount;
            if ($value != $expected) {
                $fail('Final price must be original price minus discount.');
            }
        };
    }

    private function original_price()
    {
        return function ($attribute, $value, $fail) {
            $items = request()->input('relations.items');
            if (!$items) {
                return;
            }

            $itemIds = collect($items)
                ->pluck('item_id')
                ->all();

            $totalSellPrice = DB::table('items')
                ->whereIn('id', $itemIds)
                ->sum('selling_price');

            if ($value != $totalSellPrice) {
                $fail(
                    "Original price before discount is incorrect. Provided: $value, Correct: $totalSellPrice"
                );
            }
        };
    }

    private function validPaymentMethodForType(){
        return function ($attribute, $value, $fail) {
            $type = request()->input('data.type');
            // $fail("$value -- $type");
            if ($type === 'refund' && $value !== PaymentMethod::Cash->value) {
                $fail("Refunds can only be CASH");
            }
            if ($type === 'outgoing_credit' && $value !== PaymentMethod::Cash->value) {
                $fail("Supplier credit can only be CASH");
            }
            if ($type === 'incoming_credit' && $value === 'credit') {
                $fail("Credit account payments can't be paid in credit, Muse be Cash, Visa or Instapay");
            }
        };
    }
}
