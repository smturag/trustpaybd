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
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->decimal('merchant_last_balance', 15, 2)->nullable()->after('merchant_main_amount');
            $table->decimal('merchant_new_balance', 15, 2)->nullable()->after('merchant_last_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn(['merchant_last_balance', 'merchant_new_balance']);
        });
    }
};
