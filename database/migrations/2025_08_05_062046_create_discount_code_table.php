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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed_amount']);
            $table->decimal('value', 15, 2)->comment('Discount value');
            $table->decimal('min_purchase', 15, 2)->nullable()->comment('Minimum purchase amount');
            $table->decimal('max_discount', 15, 2)->nullable()->comment('Maximum discount for percentage type');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->json('applicable_events')->nullable()->comment('List of applicable event IDs (null = all events)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index(['valid_from', 'valid_until']);
            $table->index('is_active');
            $table->index(['is_active', 'valid_from', 'valid_until'], 'discount_codes_active_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }

};
