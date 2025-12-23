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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('customer_id')->index()->nullable();
			$table->unsignedBigInteger('receiver_customer_id')->index()->nullable();
			$table->unsignedBigInteger('merchant_id')->index()->nullable();
			$table->string('type', 50)->nullable()->comment('deposit, payment, withdraw');
			$table->string('trxid')->nullable();
			$table->string('merchant_reference')->nullable();
			$table->string('payment_method', 50)->nullable();
			$table->string('agent_sim', 50)->nullable();
			$table->string('status', 50)->default(1)->comment('0=pending, 1=success, 2=reject');
			$table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
			$table->text('note')->nullable();
            $table->double('old_balance', 12,2)->default(0);
            $table->double('debit', 12,2)->default(0);
            $table->double('credit', 12,2)->default(0);
            $table->string('account_type', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
