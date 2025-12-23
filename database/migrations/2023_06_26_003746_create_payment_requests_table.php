<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
    $table->id();
    $table->string('request_id')->nullable();
    $table->string('trxid')->nullable();
    $table->string('payment_method_trx', 100)->nullable();
    $table->double('amount', 12, 2);

    // Fee & commission fields
    $table->decimal('merchant_fee', 10, 2)->default(0.00);
    $table->decimal('merchant_commission', 10, 2)->default(0.00);
    $table->decimal('sub_merchant_fee', 10, 2)->default(0.00);
    $table->decimal('sub_merchant_commission', 10, 2)->default(0.00);
    $table->decimal('merchant_main_amount', 10, 2)->default(0.00);
    $table->decimal('sub_merchant_main_amount', 10, 2)->default(0.00);
    $table->decimal('user_fee', 10, 2)->default(0.00);
    $table->decimal('user_commission', 10, 2)->default(0.00);
    $table->decimal('user_main_amount', 10, 2)->default(0.00);
    $table->decimal('partner_fee', 10, 2)->default(0.00);
    $table->decimal('partner_commission', 10, 2)->default(0.00);
    $table->decimal('partner_main_amount', 10, 2)->default(0.00);

    $table->string('payment_method')->nullable();
    $table->string('from_number', 15)->nullable();
    $table->unsignedBigInteger('merchant_id')->index()->nullable();
    $table->string('sub_merchant', 10)->nullable();
    $table->string('reference', 20)->nullable()
          ->comment('invoice_id of merchant’s order');
    $table->enum('currency', ['BDT', 'USD', 'EURO'])->default('BDT');
    $table->text('callback_url')->nullable();
    $table->string('sim_id')->nullable();

    // Customer info
    $table->text('cust_name')->nullable();
    $table->text('cust_phone')->nullable();
    $table->text('cust_address')->nullable();
    $table->text('checkout_items')->nullable();
    $table->text('note')->nullable();
    $table->text('ext_field_1')->nullable();
    $table->text('ext_field_2')->nullable();

    // Timing & relations
    $table->timestamp('issue_time')
          ->useCurrent()
          ->useCurrentOnUpdate()
          ->comment('Filled by system');
    $table->integer('agent')->nullable();
    $table->integer('dso')->nullable();
    $table->integer('partner')->nullable();
    $table->integer('modem_id')->index()->nullable();
    $table->string('device_id')->index()->nullable();
    $table->integer('customer_id')->index()->nullable();

    // System info
    $table->string('ip')->nullable();
    $table->string('user_agent')->nullable();
    $table->string('accepted_by', 200)->nullable();

    // Status & flags
    $table->string('status')->default('0')
          ->comment('1=completed, 0=pending, 2=accepted, 3=rejected etc');
    $table->text('reject_msg')->nullable();
    $table->integer('send_sms')->nullable();
    $table->boolean('balance_updated')->default(0);
    $table->boolean('merchant_balance_updated')->default(0);
    $table->string('payment_type', 5)->default('P2A');

    $table->foreign('merchant_id')
          ->references('id')->on('merchants')
          ->onUpdate('cascade')->onDelete('restrict');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
