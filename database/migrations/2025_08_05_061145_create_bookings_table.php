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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->comment('Price per ticket at booking time');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('final_amount', 15, 2);
            $table->unsignedBigInteger('discount_code_id')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled', 'refunded', 'expired'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 100)->nullable()->comment('Payment gateway reference');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('booking_date');
            $table->timestamp('expired_at')->nullable()->comment('Payment deadline');
            $table->text('notes')->nullable();
            $table->string('booker_name');
            $table->string('booker_email');
            $table->string('booker_phone', 50)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('booking_code');
            $table->index('user_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('booking_date');
            $table->index('payment_date');
            $table->index('expired_at');
            $table->index('discount_code_id');
            $table->index(['event_id', 'status', 'booking_date'], 'bookings_event_status_date');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            //            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
