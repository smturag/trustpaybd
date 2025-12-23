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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->decimal('amount', 52, 2)->default(0.0);
            $table->decimal('charge', 52, 2)->default(0.0);
            $table->decimal('old_balance', 52, 2)->default(0.0);
            $table->string('trx_type', 40)->nullable();
            $table->string('trx', 40)->nullable();
            $table->string('details', 255)->nullable();
            $table->string('user_type', 40)->nullable();
            $table->string('status',10)->default('2')->comment('1=pending, 2=confirmed , 3= reject');
            $table->string('wallet_type', 40)->nullable();
            $table->string('creator_id',10)->nullable();
            $table->string('creator_type',10)->nullable();
            $table->timestamps();
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
