<?php 
namespace App\Traits;

use App\Enums\PaymentMethod;
use App\Enums\TransactionDirection;
use App\Models\Item;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

// Only use in PersistableTransaction
trait PersistableTransactionHelper{

    use ItemStockHelper;

    private function hi2(): string{
        return 'hi';
    }


    protected function persistItemTransactoin() {
        DB::transaction(function () {
            if ($this->payment_method === PaymentMethod::Credit->value) {
                $this->manageIncomingCreditPurchase();
            }

            $items = $this->items;
            // Since no column items in transactions table 
            unset($this->items);

            $this->persistTransaction();
            $this->attachItems($items);
            $this->managePayableItemStock($items);
        });
    }

    protected function persistCreditPyamentTransaction(){
        DB::transaction(function () {
            $this->persistTransaction();
            $this->manageCreditPayment();
        });
    }

    private function persistTransaction()  {
        $this->final_price = $this->getSignedValue();
        $this->shift()->associate(Shift::getAuthUserShift());
        $this->save();
    }


    private function attachItems(array $items)
    {
        $pivotData = [];
        foreach ($items as $item) {
            $pivotData[$item['item_id']] = ['quantity' => $item['quantity']];
        }
        $this->items()->attach($pivotData);
    }



    // Manages clients paying off some of the credit balance
    // Or the store paying their dept to suppliers
    private function manageCreditPayment(){
        $amount = $this->final_price;
        $owner = $this->owner;
        $currentBalance = $owner->getCurrentBalance();
        $newBalance = $currentBalance - abs($amount);
        if ($newBalance < 0) {
            throw new InvalidArgumentException(
                "Amount paid is greater than current balance: $currentBalance, Please enter correct amount"
            );
        }

        $owner->setCurrentBalance($newBalance);
        $owner->save();
    }

    // Manage clients purchasing items in credit 
    private function manageIncomingCreditPurchase() {
        $amount = $this->final_price;
        $owner = $this->owner;
        if (!$owner->isCreditAllowed()) {
            throw new InvalidArgumentException("Owner is not allowed to use credit", 1);
        }

        $newBalance = $owner->getCurrentBalance() + abs($amount);
        $creditLimit = $owner->getCreditLimit();
        if ($creditLimit < $newBalance) {
            throw new InvalidArgumentException("Transaction will exceed credit limit $newBalance the limit is $creditLimit", 1);
        }

        $owner->setCurrentBalance($newBalance);
        $owner->save();
    }

}