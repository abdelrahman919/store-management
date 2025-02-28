<?php
namespace App\Models\Abstracts;

use App\Enums\TransactionDirection;



interface Payable{

    function getDirection(): TransactionDirection;

    function getSignedValue(): float;

    // whether it has items like sale or not like creditpayments
    function hasItems(): bool;

}