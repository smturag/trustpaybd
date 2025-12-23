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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
			$table->string('member_code')->nullable();
			$table->string('pincode')->default(1234);
            $table->string('mobile')->unique();
			$table->string('password');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
			$table->string('balance')->decimal(52,2)->default(0);
			$table->string('limit')->decimal(52,2)->default(0);
			$table->string('user_type')->default('general');
            $table->boolean('auto_active_agent')->default(0);
			$table->string('create_by')->nullable();
			$table->string('status')->default(1);
			$table->string('partner')->default('-1');
			$table->string('dso')->default('-1');
			$table->string('agent')->default('-1');
			$table->string('last_ip')->nullable();
            $table->string('last_login')->nullable();
            $table->string('last_login_count')->default(0);
            $table->string('otp')->default('no');
            $table->string('otp_type')->nullable();
            $table->string('otp_code')->nullable();
            $table->string('otp_google_code')->nullable();
            $table->string('pass_expire')->nullable();
            $table->string('pin_expire')->nullable();
            $table->string('access_code')->nullable();
            $table->integer('safepass')->default(0);
            $table->string('db_status')->default('live');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
