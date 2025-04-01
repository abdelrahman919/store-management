<?php

use App\Enums\SettingsKeys;
use App\Models\OrderEntry;
use function Pest\Laravel\json;

use App\Models\transactions\Sale;
use Illuminate\Database\Eloquent\Casts\Json;
use App\Http\Controllers\TransactionController;
use App\Models\SettingsConfig;

require_once __DIR__ . '/vendor/autoload.php';



// $id = 1;
// $stockDiff = 5;
// $itemStockDiff[] = ['id'=>$id, 'diff'=>$stockDiff];
// $itemStockDiff[] = ['id'=>2, 'diff'=>3];
// $collect = collect($itemStockDiff);

// $updatedEntryObjetcs = collect($entriesData)->map(fn ($entry) => new OrderEntry($entry))->toArray();

// echo $calculatedTotal


// $s = 'string';
// $int = 1;
// $bool = true;
// $float = 1.1;

// $sType = gettype($s);
// $intType = gettype($int);
// echo $sType ;
// echo $intType;
// echo gettype($bool);
// echo gettype($float);

$ar1 = ['hi', 'hello'];
$ar2 = ['hi2', 'hello2'];



print_r(gettype(json_encode(['value' => 0])));


?>