<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Services\PurchaseOrderService;

class PurchaseOrderController extends BaseController
{
    private PurchaseOrderService $service;

    public function __construct(PurchaseOrderService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(PurchaseOrder::with('entries')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        $validated = $request->validated();
        $order = $this->service->storeOrder($validated);
        return $this->created($order->load('entries'));
    }


    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $order)
    {
        return $this->success($order->load(['entries.item', 'shift']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $order)
    {
        $validated = $request->validated();
        $this->service->updateOrder($validated, $order);
        return $this->success($order->load('entries'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $order)
    {
        //
    }


}
