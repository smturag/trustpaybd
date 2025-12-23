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
        Schema::create('sms_inboxes', function (Blueprint $table) {
            $table->id();
			$table->string('sender')->nullable();
            $table->text('sms')->nullable();
			$table->string('sim_slot')->nullable();
			$table->string('device_id')->nullable();
			$table->string('sms_time')->nullable();
            $table->string('modem')->nullable();
            $table->string('type')->nullable();
            $table->string('newentry')->nullable();
			$table->string('partner')->default('-1');
			$table->string('dso')->default('-1');
			$table->string('agent')->default('-1');
			$table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_inboxes');
    }
};
