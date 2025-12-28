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
        Schema::create('support_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('uploaded_by_type')->default('merchant'); // merchant, admin, agent, customer
            $table->unsignedBigInteger('uploaded_by_id');
            $table->timestamps();
            
            $table->index('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_attachments');
    }
};
