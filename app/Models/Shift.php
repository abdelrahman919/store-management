<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory;

    public $guarded = [];
    public $timestamps = false; 


    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }


    public function purchaseOrders(): HasMany{
        return $this->hasMany(PurchaseOrder::class);
    }


    public function scopeActive($query, $userId){
        return $query->where('user_id', $userId)
        ->whereNull('end')
        ->first();
    }

    public static function getAuthUserShift(): ?Shift{
        return self::active(Auth::user()->id);
    }

}
