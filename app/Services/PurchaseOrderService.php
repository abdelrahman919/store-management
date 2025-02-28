<?php 
namespace App\Services;

use App\Models\Shift;
use App\Models\OrderEntry;
use App\Models\PurchaseOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService{

    private ItemService $itemService;

    public function __construct(ItemService $itemService) {
        $this->itemService = $itemService;      
    }

    public function storeOrder(array $validated): PurchaseOrder{
        $order = new PurchaseOrder();

        DB::transaction(function () use ($validated, &$order) {
            $order = $this->persistOrder($validated);
            $entryCollection = collect($validated['relations']['entries']);
            $this->persistEntries($entryCollection, $order->id);
            $itemIdQuantityArray = $entryCollection->map(function ($entry) {
                return [
                    'item_id'=>$entry['item_id'],
                    'quantity'=>$entry['quantity']
                ];
            })->toArray();
            $this->itemService->managePayableItemStock($order, $itemIdQuantityArray);
        });
        return $order;
    }


    public function updateOrder(array $validated, PurchaseOrder $order): PurchaseOrder{
        DB::transaction(function () use ($validated, &$order) {
            if (array_key_exists('data', $validated) && filled($validated['data'])) {
                $order->update($validated['data']);
            }
            if (array_key_exists('relations', $validated) && filled($validated['relations'])) {
                $entriesData = $validated['relations']['entries'];
                $updatedEntryObjects = collect($entriesData)->map(fn ($entry) => new OrderEntry($entry))->all();
                $this->itemService->manageUpdatedOrderItemStock($updatedEntryObjects);
                // Use updateOrCreate for if user adds more entries 
                $order->entries()->updateOrCreate($entriesData);
            }  
        });
        return $order;
    }


    private function persistOrder(array $validated): PurchaseOrder{
        $shift = Shift::getAuthUserShift();

        return PurchaseOrder::create([
            ...$validated['data'],
            'supplier_id'=>$validated['relations']['supplier_id'],
            'shift_id' => $shift->id
        ]);
    }

    private function persistEntries(Collection $entryCollection , $orderId){
        $entries = $entryCollection->map(function ($entry) use ($orderId) {
            return [
                'purchase_order_id' => $orderId,
                'item_id' => $entry['item_id'],
                'total_price' => $entry['total_price'],
                'cost_price' => $entry['cost_price'],
                'quantity' => $entry['quantity'],
                'created_at' => now(),
                // 'updated_at' => now(),
            ];
        })->toArray();
        OrderEntry::insert($entries);
    }



}