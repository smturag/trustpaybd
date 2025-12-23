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
        Schema::create('user_charges', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mfs_operator_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['deposit', 'withdraw']);
            $table->decimal('fee', 10, 2)->default(0); // percentage %
            $table->decimal('commission', 10, 2)->default(0); // percentage %
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_charges');
    }
};
