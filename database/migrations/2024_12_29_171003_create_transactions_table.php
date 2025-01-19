<?php

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use App\Models\Shift;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            // Client or Supplier [auto creates index for morph columns]
            $table->nullableMorphs('owner');
            $table->foreignIdFor(Shift::class);
            $table->enum('payment_method', PaymentMethod::getValues())->default(PaymentMethod::Cash->value);
            $table->float('original_price')->default(0);
            $table->float('discount')->default(0);
            $table->float('final_price')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();

            // // for better query performance 
            // $table->index(['transactionable_id', 'transactionable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
