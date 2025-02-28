<?php

namespace App\Dtos;

use App\Models\Abstracts\PersistableTransaction;

class TransactionStoreDto
{
    private PersistableTransaction $persistableTransaction;
    private array $data;

    public function __construct(PersistableTransaction $persistableTransaction, array $data = [])
    {
        $this->persistableTransaction = $persistableTransaction;
        $this->data = $data;
    }

    public static function fromPersistableTransaction(PersistableTransaction $persistableTransaction, array $data = []): TransactionStoreDto{
        return new TransactionStoreDto($persistableTransaction, $data);
    }

    public function getPersistableTransaction(): PersistableTransaction
    {
        return $this->persistableTransaction;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setPersistableTransaction(PersistableTransaction $persistableTransaction)
    {
        $this->persistableTransaction = $persistableTransaction;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
}

