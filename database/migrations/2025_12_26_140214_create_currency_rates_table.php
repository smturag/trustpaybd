<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 10)->unique()->comment('Currency code like BDT, USD, EUR');
            $table->string('currency_name', 50)->comment('Full currency name');
            $table->string('currency_symbol', 10)->nullable()->comment('Currency symbol like $, €, ৳');
            $table->decimal('exchange_rate_to_bdt', 15, 6)->default(1.000000)->comment('Exchange rate to BDT (base currency)');
            $table->tinyInteger('status')->default(1)->unsigned()->comment('0: Inactive, 1: Active');
            $table->timestamps();
        });

        // Insert default currencies
        DB::table('currency_rates')->insert([
            [
                'currency_code' => 'BDT',
                'currency_name' => 'Bangladeshi Taka',
                'currency_symbol' => '৳',
                'exchange_rate_to_bdt' => 1.000000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'USD',
                'currency_name' => 'US Dollar',
                'currency_symbol' => '$',
                'exchange_rate_to_bdt' => 110.000000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'exchange_rate_to_bdt' => 120.000000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'GBP',
                'currency_name' => 'British Pound',
                'currency_symbol' => '£',
                'exchange_rate_to_bdt' => 140.000000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'INR',
                'currency_name' => 'Indian Rupee',
                'currency_symbol' => '₹',
                'exchange_rate_to_bdt' => 1.350000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
