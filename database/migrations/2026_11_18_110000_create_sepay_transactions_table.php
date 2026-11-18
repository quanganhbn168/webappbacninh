<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table the sepayvn/laravel-sepay webhook writes every bank transfer into (from the
 * package's migration stub, which was never published). The id is SePay's own id.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sepay_transactions')) {
            return;
        }

        Schema::create('sepay_transactions', function (Blueprint $table): void {
            $table->id();
            $table->string('gateway');
            $table->string('transactionDate');
            $table->string('accountNumber');
            $table->string('subAccount')->nullable();
            $table->string('code')->nullable();
            $table->string('content');
            $table->string('transferType');
            $table->string('description', 1000)->nullable();
            $table->bigInteger('transferAmount');
            $table->string('referenceCode')->nullable();
            $table->timestamps();

            $table->index(['transferType', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sepay_transactions');
    }
};
