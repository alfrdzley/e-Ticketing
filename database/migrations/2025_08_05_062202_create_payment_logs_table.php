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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('transaction_id', 100)->nullable()->comment('Payment gateway transaction ID');
            $table->string('payment_method', 50);
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'expired']);
            $table->json('gateway_response')->nullable()->comment('Full response from payment gateway');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('booking_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('processed_at');
            $table->index(['booking_id', 'status']);

            // Foreign key
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
