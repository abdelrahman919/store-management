<?php

namespace App\Http\Controllers;

use App\Dtos\TransactionStoreDto;
use App\Factories\ModelFactories\PersistableTransactionFactory;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Abstracts\PersistableTransaction;
use App\Models\transactions\IncomingCreditPayment;
use App\Models\transactions\OutgoingCreditPayment;
use App\Models\transactions\Refund;
use App\Models\transactions\Sale;
use App\Services\TransactionService;

class TransactionController extends BaseController
{

    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // Type MUST be mapped to PersistableTransaction child
    public const TYPE_MAP = [
        'sale' => Sale::class,
        'refund' => Refund::class,
        'incoming_credit' => IncomingCreditPayment::class,
        'outgoing_credit' => OutgoingCreditPayment::class
    ];


    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $persistableTransaction = $this->transactionService->persist($validated);
        /*  $transactionStoreDto = $this->mapTransaction($validated);

        $persistableTransaction = $transactionStoreDto->getPersistableTransaction();
        $data = $transactionStoreDto->getData();
        $persistableTransaction->persist($this->transactionService, $data); */

        // $persistableTransaction->persist();
        return $this->success($persistableTransaction->load(['owner', 'items']));
    }


    private function mapTransaction($validated): TransactionStoreDto
    {
        $transaction = PersistableTransactionFactory::create($validated);
        $transactionStoreDto = new TransactionStoreDto($transaction);
        if ($transaction->shouldHaveItems()) {
            $transactionStoreDto->setData($validated['relations']['items']);
            // $transaction->items = $validated['relations']['items'];
        }
        return $transactionStoreDto;
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $includedRelations = ['shift', 'owner'];
        if ($transaction->items()) {
            $includedRelations[] = 'items';
        }

        return $this->success($transaction->load($includedRelations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
