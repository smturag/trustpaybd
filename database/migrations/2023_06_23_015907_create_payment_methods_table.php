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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mobile_banking');
            $table->foreign('mobile_banking')->references('id')->on('mfs_operators')->onDelete('cascade');
            $table->string('type');
            $table->string('member_code');
            $table->string('sim_id');
            $table->enum('process_type', ['live', 'manual'])->nullable();
            $table->tinyInteger('status')->default(1)->unsigned()->comment('0: Off, 1: On');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
