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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('event');
            $table->string('user_agent', 500)->nullable()->after('ip_address');
            $table->string('device')->nullable()->after('user_agent');
            $table->string('browser')->nullable()->after('device');
            $table->string('platform')->nullable()->after('browser');
            $table->string('country', 100)->nullable()->after('platform');
            $table->string('country_code', 10)->nullable()->after('country');
            $table->string('city', 100)->nullable()->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'user_agent',
                'device',
                'browser',
                'platform',
                'country',
                'country_code',
                'city'
            ]);
        });
    }
};
