<?php

namespace App\Models;

use App\Traits\ItemStockHelper;
use App\Models\Abstracts\Payable;
use App\Enums\TransactionDirection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model implements Payable
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;
    // use ItemStockHelper;

    public $guarded = [];

    public function entries(): HasMany {
        return $this->hasMany(OrderEntry::class);
    }

    public function shift(): BelongsTo{
        return $this->belongsTo(Shift::class);
    }

    function getDirection(): TransactionDirection
    {
        return TransactionDirection::Outgoing;
    }

    function hasItems(): bool
    {
        return true;
    }

    function getSignedValue(): float
    {
        return $this->total_price * -1;
    }
}
