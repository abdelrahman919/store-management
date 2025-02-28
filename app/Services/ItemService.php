<?php 
namespace App\Services;

use App\Models\Item;
use App\Models\OrderEntry;
use App\Enums\TransactionDirection;
use App\Models\Abstracts\Payable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ItemService
{

    // Fetch and save all at once to avoid multiple DB calls
    /**
     * @param Payable "ex: Transaction & PurchaseOrder"
     * @param array <BigInteger itemdId, int quantity>
     * 
     *  */
    public function managePayableItemStock(Payable $payable ,array $itemQuantity)
    {
        $itemIds = array_column($itemQuantity, 'item_id');
        $dbItems = Item::whereIn('id', $itemIds)->lockForUpdate()->get();

        foreach ($itemQuantity as $item) {
            $currentItemdId = $item['item_id'];
            $quantity = $item['quantity'];

            $currentDbItem = $dbItems->firstWhere($currentItemdId, $item->id);

            // TODO: DISCUSS CHECK FOR NEGATIVE STOCK VALUE 
            $$payable->getDirection() === TransactionDirection::Incoming ?
                $currentDbItem->stock -= $quantity :
                $currentDbItem->stock += $quantity;
        }
        $dbItems->each->save();
    }


    /**
     * Update items stock after updating Purchase Order entires
     * 
     * @param array <OrderEntry> the updated entries 
     * 
     *  */
    public function manageUpdatedOrderItemStock(array $updatedEntries)
    {
        $entriesIds = array_column($updatedEntries, 'id');

        // Use lockForUpdate to stop read and write on etries
        $oldEntires = OrderEntry::whereIn('id', $entriesIds)->lockForUpdate()->get();
        $itemIdDiffMap = $this->calculateOrderStockDifference($updatedEntries, $oldEntires)->keyBy('id');
        $itemIds = $itemIdDiffMap->pluck('id')->toArray();

        // Use lockForUpdate to avoid race condtion from other transactions altering item stock
        $items = Item::whereIn('id', $itemIds)->lockForUpdate()->get();
        foreach ($items as $item) {
            $stockDifference = $itemIdDiffMap->get($item->id)['diff'];
            $item->stock += $stockDifference;
        }
        $items->each->save();
    }


    // TODO: account for user changing the item in an entry 
    private function calculateOrderStockDifference(array $updatedEntries, EloquentCollection $oldEntires): Collection
    {
        $itemStockDiff = [];
        $oldEntiresMap = $oldEntires->keyBy('id');
        foreach ($updatedEntries as $updatedEntry) {
            $entryId = $updatedEntry->id ?? null;
            $itemId = $updatedEntry->item->id;

            // Handle user adding new entries 
            if (!$entryId) {
                $itemStockDiff[] = ['id' => $itemId, 'diff' => $updatedEntry->quantity];
                continue;
            }

            $entryBeforeUpdate = $oldEntiresMap->get($entryId);
            $oldEntryItemId = $entryBeforeUpdate->item->id;
            $oldEntryQuantity = $entryBeforeUpdate->quantity;

            // Handles same item but updated quantity
            if ($oldEntryItemId === $itemId) {
                $stockDiff = $updatedEntry->quantity - $oldEntryQuantity;
                $itemStockDiff[] = ['id' => $itemId, 'diff' => $stockDiff];
            // Handle the item itself 
            } else {
                $itemStockDiff[] = ['id' => $oldEntryItemId, 'diff' => -$oldEntryQuantity];
                $itemStockDiff[] = ['id' => $itemId, 'diff' => -$updatedEntry->quantity];
            }
        }
        return collect($itemStockDiff);
    }
}

