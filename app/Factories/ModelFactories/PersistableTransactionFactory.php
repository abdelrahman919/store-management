<?php 
namespace App\Factories\ModelFactories;

use App\Models\Abstracts\PersistableTransaction;
use App\Models\transactions\Sale;
use App\Models\transactions\Refund;
use App\Models\transactions\IncomingCreditPayment;
use App\Models\transactions\OutgoingCreditPayment;


class PersistableTransactionFactory{
    
    public const TYPE_MAP = [
        'sale' => Sale::class,
        'refund' => Refund::class,
        'incoming_credit' => IncomingCreditPayment::class,
        'outgoing_credit' => OutgoingCreditPayment::class
    ];


    public static function create(array $validated): PersistableTransaction{
        $class = self::TYPE_MAP[$validated['data']['type']];
        return new $class($validated['data']);
    }

    public static function getItemRequiredTypes(){
        return ['sale', 'refund'];
    }

    public static function getTypes(){
        return array_keys(self::TYPE_MAP);
    }

}