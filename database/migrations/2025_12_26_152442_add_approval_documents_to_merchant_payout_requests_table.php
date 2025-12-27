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
        Schema::table('merchant_payout_requests', function (Blueprint $table) {
            $table->text('approval_documents')->nullable()->after('admin_note')->comment('JSON array of document paths uploaded during approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_payout_requests', function (Blueprint $table) {
            $table->dropColumn('approval_documents');
        });
    }
};
