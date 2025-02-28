<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderEntry extends Model
{
    public $guarded = [];

    public function item(): BelongsTo{
        return $this->belongsTo(Item::class);
    }
}
