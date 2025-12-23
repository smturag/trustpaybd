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
        Schema::create('balance_managers', function (Blueprint $table) {
            $table->id();
			$table->string('request_time')->nullable();
			$table->string('sender')->nullable();
			$table->string('sim')->nullable();
			$table->string('oldbal')->nullable();
			$table->string('amount')->nullable();
			$table->string('commission')->nullable();
			$table->string('lastbal')->nullable();
			$table->string('mobile')->nullable();
			$table->string('trxid')->nullable();
			$table->string('sms_time')->nullable();
			$table->text('sms_body')->nullable();
			$table->string('status')->default(0);
			$table->string('type')->nullable();
			$table->string('simslot')->nullable();
			$table->string('deviceid')->nullable();
			$table->string('telco')->nullable();
			$table->string('smbal')->nullable();
			$table->text('note')->nullable();
			$table->text('ext_field')->nullable();
			$table->text('ext_field_2')->nullable();
			$table->text('ext_field_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_managers');
    }
};
