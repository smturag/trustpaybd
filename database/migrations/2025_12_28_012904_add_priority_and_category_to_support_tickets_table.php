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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('priority')->default('medium')->after('status'); // low, medium, high, urgent
            $table->string('category')->nullable()->after('priority'); // technical, billing, general, etc
            $table->timestamp('last_reply_at')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['priority', 'category', 'last_reply_at']);
        });
    }
};
