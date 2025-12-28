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
        Schema::create('merchant_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->string('type')->default('support_reply'); // support_reply, support_created, etc.
            $table->string('title');
            $table->text('message')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->string('ticket_number')->nullable();
            $table->boolean('is_read')->default(0);
            $table->timestamps();
            
            $table->index(['merchant_id', 'is_read']);
            $table->index('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_notifications');
    }
};
