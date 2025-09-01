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
        // First, add new columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->char('booking_id', 26)->nullable()->after('id');
            $table->string('midtrans_order_id')->unique()->nullable()->after('booking_id');
            $table->string('payment_method')->nullable()->after('status');
            $table->text('snap_token')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
            $table->json('midtrans_response')->nullable()->after('paid_at');
            $table->string('transaction_id')->nullable()->after('midtrans_response');
            $table->string('fraud_status')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('fraud_status');
            $table->decimal('gross_amount', 15, 2)->nullable()->after('payment_type');
        });

        // Update existing columns precision
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change();
        });

        // Drop old foreign keys
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);
        });

        // Drop old columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['event_id', 'user_id']);
        });

        // Update status column and add new constraints
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled'])->default('pending')->change();
            $table->foreign('booking_id')->references('ulid')->on('bookings')->onDelete('cascade');
            $table->index('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key and index
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropIndex(['midtrans_order_id']);
        });

        // Add back old columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->bigInteger('event_id')->unsigned()->after('id');
            $table->bigInteger('user_id')->unsigned()->after('event_id');
        });

        // Add back old foreign keys
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Remove Midtrans columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'booking_id',
                'midtrans_order_id',
                'payment_method',
                'snap_token',
                'paid_at',
                'midtrans_response',
                'transaction_id',
                'fraud_status',
                'payment_type',
                'gross_amount'
            ]);
        });

        // Restore original status and total_price
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->decimal('total_price', 10, 2)->change();
        });
    }
};
