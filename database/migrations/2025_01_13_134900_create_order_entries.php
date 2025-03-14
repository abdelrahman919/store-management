<?php

use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PurchaseOrder::class)->constrained();
            $table->foreignIdFor(Item::class)->constrained();
            $table->unsignedBigInteger('quantity');
            $table->float('cost_price');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_entries');
    }
};
