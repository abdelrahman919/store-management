<?php

use App\Enums\PaymentMethod;
use App\Models\Shift;
use App\Models\Supplier;
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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class)->constrained();
            $table->foreignIdFor(Shift::class)->constrained();
            $table->enum('payment_method', PaymentMethod::getValues())->default(PaymentMethod::Cash->value);
            $table->string('order_code');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
