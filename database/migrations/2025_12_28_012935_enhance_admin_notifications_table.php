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
        // Check if admin_notifications table exists
        if (!Schema::hasTable('admin_notifications')) {
            Schema::create('admin_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('user_type')->default('admin'); // admin, merchant
                $table->string('title');
                $table->text('message');
                $table->string('ticket_id')->nullable();
                $table->boolean('is_read')->default(0);
                $table->string('notification_type')->nullable(); // ticket_created, ticket_reply, etc
                $table->timestamps();
            });
        } else {
            // Add columns if they don't exist
            Schema::table('admin_notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('admin_notifications', 'user_type')) {
                    $table->string('user_type')->default('admin')->after('user_id');
                }
                if (!Schema::hasColumn('admin_notifications', 'title')) {
                    $table->string('title')->after('user_type');
                }
                if (!Schema::hasColumn('admin_notifications', 'message')) {
                    $table->text('message')->after('title');
                }
                if (!Schema::hasColumn('admin_notifications', 'ticket_id')) {
                    $table->string('ticket_id')->nullable()->after('message');
                }
                if (!Schema::hasColumn('admin_notifications', 'is_read')) {
                    $table->boolean('is_read')->default(0)->after('ticket_id');
                }
                if (!Schema::hasColumn('admin_notifications', 'notification_type')) {
                    $table->string('notification_type')->nullable()->after('is_read');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the entire table, just the new columns we added
        if (Schema::hasTable('admin_notifications')) {
            Schema::table('admin_notifications', function (Blueprint $table) {
                $columns = ['user_type', 'title', 'message', 'ticket_id', 'is_read', 'notification_type'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('admin_notifications', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
