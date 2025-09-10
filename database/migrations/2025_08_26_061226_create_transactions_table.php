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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('booking_id');
            $table->string('midtrans_order_id')->unique();
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            $table->string('fraud_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount', 15, 2)->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('booking_id')->references('ulid')->on('bookings')->onDelete('cascade');

            // Indexes
            $table->index('booking_id');
            $table->index('status');
            $table->index('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
