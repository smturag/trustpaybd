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
        Schema::table('merchant_payout_requests', function (Blueprint $table) {
            $table->string('merchant_currency', 10)->default('BDT')->after('amount')->comment('Merchant preferred currency');
            $table->decimal('merchant_amount', 20, 2)->after('merchant_currency')->comment('Amount in merchant currency');
            $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('merchant_amount')->comment('Exchange rate used');
            $table->decimal('bdt_amount', 20, 2)->after('exchange_rate')->comment('Converted amount in BDT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_payout_requests', function (Blueprint $table) {
            $table->dropColumn(['merchant_currency', 'merchant_amount', 'exchange_rate', 'bdt_amount']);
        });
    }
};
