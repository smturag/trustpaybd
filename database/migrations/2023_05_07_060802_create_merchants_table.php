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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
			$table->string('fullname');
			$table->string('username')->nullable();
			$table->string('pincode')->default(1234);
            $table->string('mobile')->unique();
			$table->string('password');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
			$table->string('balance')->decimal(52,2)->default(0);
			$table->string('limit')->decimal(52,2)->default(0);
			$table->string('merchant_type')->default('general');
			$table->string('create_by')->nullable();
			$table->string('status')->default(1);
			$table->string('last_ip')->nullable();
            $table->string('last_login')->nullable();
            $table->string('last_login_count')->default(0);
			$table->string('pass_expire')->nullable();
            $table->string('pin_expire')->nullable();
            $table->string('access_code')->nullable();
            $table->integer('safepass')->default(0);
            $table->boolean('v1_p2a')->default(true);
            $table->boolean('v1_p2c')->default(true);
            $table->boolean('v1_p2p')->default(true);
            $table->boolean('v1_direct_gateway')->default(true);
            $table->boolean('v1_manual_gateway')->default(true);
			$table->string('note')->nullable();
            $table->string('db_status')->default('live');
			$table->string('api_key')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
