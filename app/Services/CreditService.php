<?php 

class CreditService{



    public function payCredit(Owner $owner, float $amount){
        $newBalance = $owner->getCurrentBalance() - $amount;
        if ($newBalance < 0) {
            throw new InvalidArgumentException(
                "Amount paid is greater than current balane, Please enter correct amount"
            );
        }

        $owner->setCurrentBalance($newBalance);
        $owner->save();
    }


    public function addToBalance(Owner $owner, float $amount) {
        if (!$owner->isCreditAllowed()) {
            throw new InvalidArgumentException("Owner is not allowed to use credit", 1);
        }

        $newBalance = $owner->getCurrentBalance() + $amount;
        if ($owner->getCreditLimit() < $newBalance) {
            throw new InvalidArgumentException("Owner exceeded the credit limit, Can't complete transaction", 1);
        }

        $owner->setCurrentBalance($newBalance);
        $owner->save();
    }

}