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
        Schema::create('user_access_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('user_type');
            $table->string('ip_address');
            $table->string('tokenid')->nullable();
            $table->string('session_id')->nullable();
            $table->string('browser')->nullable();
            $table->string('login_with')->nullable();
            $table->string('platform')->nullable();
            $table->string('pincode')->nullable();
            $table->string('accesscode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_access_logs');
    }
};
