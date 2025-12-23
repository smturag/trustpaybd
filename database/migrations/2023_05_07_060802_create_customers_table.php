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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
			$table->string('customer_name');
            $table->string('mobile', 30)->unique();
			$table->string('password');
			$table->string('pin_code', 20)->default(1234);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
			$table->decimal('balance', 12,2)->default(0);
			$table->decimal('limit', 12,2)->default(0);
			$table->string('type', 50)->default('personal');
			$table->string('status', 50)->default(1);
			$table->ipAddress('last_ip')->nullable();
            $table->string('last_login')->nullable();
            $table->string('last_login_count')->default(0);
			$table->string('pass_expire')->nullable();
            $table->string('pin_expire')->nullable();
            $table->string('access_code')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->string('mobile_verification_token')->nullable();
            $table->integer('safe_pass')->default(0);
			$table->text('note')->nullable();
            $table->string('db_status', 50)->default('live');
            $table->string('payment_status', 50)->nullable()->comment('processing, success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
