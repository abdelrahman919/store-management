<?php

use App\Models\OrderEntry;
use function Pest\Laravel\json;

use App\Models\transactions\Sale;
use Illuminate\Database\Eloquent\Casts\Json;
use App\Http\Controllers\TransactionController;

require_once __DIR__ . '/vendor/autoload.php';



// $id = 1;
// $stockDiff = 5;
// $itemStockDiff[] = ['id'=>$id, 'diff'=>$stockDiff];
// $itemStockDiff[] = ['id'=>2, 'diff'=>3];
// $collect = collect($itemStockDiff);

// $updatedEntryObjetcs = collect($entriesData)->map(fn ($entry) => new OrderEntry($entry))->toArray();

// echo $calculatedTotal


$a = ['sale', 'refund'];

echo json_encode("true")


?>