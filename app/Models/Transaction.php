<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\TransactionDirection;
use App\Models\Abstracts\Payable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model implements Payable
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'transactions';


    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_transaction', 'transaction_id', 'item_id')
            ->withPivot('quantity');
    }


    protected static function boot()
    {
        parent::boot();

        // Event listner to the create event "only triggers on create not update"
        // Automatically fills the type with modle class name when created
        static::creating(function ($model) {
            $model->type = static::class;
            // if (empty($model->type)) {
            //     $model->type = static::class;
            // }
        });


        // Making sure a global scope is added to all children classes 
        // While ignoring the "type" filter if the class is the parent  
        static::addGlobalScope('type', function (Builder $builder) {
            if (static::class !== self::class) {
                $builder->where('type', static::class);
            }
        });
    }


    public function scopeCredit($query, $isCredit = true)
    {
        return $isCredit
        ? $query->where('payment_method', PaymentMethod::Credit->value)
        : $query->where('payment_method', '!=', PaymentMethod::Credit->value);
    }


    public function setOwnerAttribute($value){
        $allowedClasses = [Client::class, Supplier::class];

        if (! in_array(get_class($value), $allowedClasses)) {
            throw new \InvalidArgumentException('Owner must be a Clinet or Supplier');
        }
    }

    function getSignedValue(): float
    {
        return $this->final_price;
    }

    function hasItems(): bool
    {
        return $this->items()->exists();
    }

    function getDirection(): TransactionDirection
    {
        return $this->final_price < 0 ?
        TransactionDirection::Outgoing :
        TransactionDirection::Incoming ;
    }

    



}



/*     // Sale Refun or PurchaseOrders 
    public function transactionable(): MorphTo{
        return $this->morphTo();
    } */
