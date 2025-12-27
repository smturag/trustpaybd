<?php

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
        Schema::create('merchant_payout_requests', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->unique();
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('sub_merchant')->nullable();
            $table->unsignedBigInteger('crypto_currency_id')->nullable();
            $table->string('currency_name')->nullable(); // USDT, BTC, etc.
            $table->string('network')->nullable(); // TRON_(TRC20), ERC20, etc.
            $table->string('wallet_address');
            $table->decimal('amount', 20, 2);
            $table->decimal('fee', 20, 2)->default(0);
            $table->decimal('net_amount', 20, 2);
            $table->decimal('old_balance', 20, 2);
            $table->decimal('new_balance', 20, 2);
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=processing, 2=approved, 3=rejected, 4=completed');
            $table->string('transaction_hash')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('reject_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('sub_merchant')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('crypto_currency_id')->references('id')->on('crypto_currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_payout_requests');
    }
};
