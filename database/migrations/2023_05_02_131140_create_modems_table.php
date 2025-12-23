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
        Schema::create('modems', function (Blueprint $table) {
            $table->id();
			$table->string('type');
			$table->string('member_code');
            $table->string('operator');
            $table->string('sim_id');
            $table->string('sim_number');
			$table->string('partner')->default('-1');
			$table->string('dso')->default('-1');
			$table->string('agent')->default('-1');
			$table->integer('busy')->default(0);
			$table->integer('status')->default(0);
            $table->enum('transaction_type', ['P2C','P2A'])->default('P2A');
            $table->integer('operating_status')->comment('0=off, 1=cash_in, 2=cash_out,3=both_on')->default(0);
			$table->text('modem_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modems');
    }
};
