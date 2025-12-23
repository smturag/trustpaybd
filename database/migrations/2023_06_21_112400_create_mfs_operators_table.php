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
        Schema::create('mfs_operators', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('image');
            $table->string('type',20);
            $table->decimal('deposit_fee',10,2)->default(0);
            $table->decimal('deposit_commission',10,2)->default(0);
            $table->decimal('withdraw_fee',10,2)->default(0);
            $table->decimal('withdraw_commission',10,2)->default(0);
            $table->tinyInteger('status')->default(1)->unsigned()->comment('0: Off, 1: On');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mfs_operators');
    }
};
