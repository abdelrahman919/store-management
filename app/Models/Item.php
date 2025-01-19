<?php

namespace App\Models;

use App\Models\Code;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    public $guarded = [];

    public function Codes(): HasMany{
        return $this->hasMany(Code::class);
    }
}
