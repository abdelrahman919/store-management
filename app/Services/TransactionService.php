<?php
namespace App\Services;

use App\Models\Item;
use App\Models\Shift;
use App\Enums\PaymentMethod;
use InvalidArgumentException;
use App\Dtos\TransactionStoreDto;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionDirection;
use App\Models\Abstracts\PersistableTransaction;
use App\Factories\ModelFactories\PersistableTransactionFactory;

class TransactionService{

    public function persist($validated){
        $transaction = PersistableTransactionFactory::create($validated);
        $relations = isset($validated['relations']) ? $validated['relations'] : [];
        $transactionStoreDto = TransactionStoreDto::fromPersistableTransaction($transaction, $relations);
        
        $persistableTransaction = $transactionStoreDto->getPersistableTransaction();
        $data = $transactionStoreDto->getData();
        $persistableTransaction->persist($this, $data);
        return $transaction;

    }


    public function persistItemTransactoin(PersistableTransaction $transaction, array $relations) {
        $items = $relations['items'];
        DB::transaction(function () use ($items, &$transaction) {
            if ($transaction->payment_method === PaymentMethod::Credit->value) {
                $this->manageCreditPurchase($transaction);
            }
            $this->persistTransaction($transaction);
            $this->attachItems($transaction, $items);
            $this->manageStock($transaction, $items);
        });
    }

    public function persistCreditPyamentTransaction(PersistableTransaction $transaction){
        DB::transaction(function () use ($transaction) {
            $this->persistTransaction($transaction);
            $this->manageCreditPayment($transaction);
        });
    }

    private function persistTransaction(PersistableTransaction $transaction)  {
        $transaction->final_price = $transaction->getSignedValue();
        $transaction->shift()->associate(Shift::getAuthUserShift());
        $transaction->save();
    }


    private function attachItems(PersistableTransaction $transaction, array $items)
    {
        $pivotData = [];
        foreach ($items as $item) {
            $pivotData[$item['item_id']] = ['quantity' => $item['quantity']];
        }
        $transaction->items()->attach($pivotData);
    }

    // Fetch and save all at once to avoid multiple DB connections
    private function manageStock(PersistableTransaction $transaction, array $itemQuantity){
        $itemIds = array_column($itemQuantity, 'item_id');
        $dbItems = collect(Item::whereIn('id', $itemIds)->get());
        
        foreach ($itemQuantity as $item) {
            $currentItemdId = $item['item_id'];
            $quantity = $item['quantity'];

            $currentDbItem = $dbItems->first(function ($dbItem) use ($currentItemdId) {
                return $dbItem->id === $currentItemdId;
            });
            // TODO: DISCUSS CHECK FOR NEGATIVE STOCK VALUE 
            $transaction->getDirection() === TransactionDirection::Incoming ?
            $currentDbItem->stock -= $quantity :
            $currentDbItem->stock += $quantity ;
        }
        $dbItems->each->save();
    }

    // Manages owners paying off some of the credit balance
    private function manageCreditPayment(PersistableTransaction $transaction ){
        $amount = $transaction->final_price;
        $owner = $transaction->owner;
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
    private function manageCreditPurchase(PersistableTransaction $transaction) {
        $amount = $transaction->final_price;
        $owner = $transaction->owner;
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