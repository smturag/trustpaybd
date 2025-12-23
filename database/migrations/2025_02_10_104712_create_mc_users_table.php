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
        Schema::create('mc_users', function (Blueprint $table) {
            $table->id();
            $table->string('userid')->nullable();
            $table->string('password')->nullable();
            $table->string('workcode')->nullable();
            $table->string('currency')->nullable();
            $table->string('location')->nullable();
            $table->string('appguid')->nullable();
            $table->integer('status')->default(0);
            $table->text('ext_1')->nullable();
            $table->text('ext_2')->nullable();
            $table->text('ext_3')->nullable();
            $table->text('ext_4')->nullable();
            $table->text('ext_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mc_users');
    }
};
