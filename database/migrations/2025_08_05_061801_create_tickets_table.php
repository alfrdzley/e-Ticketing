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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 20)->unique();
            $table->string('qr_code', 500)->nullable()->comment('QR code image path/URL');
            $table->unsignedBigInteger('booking_id');
            $table->string('attendee_name')->nullable();
            $table->string('attendee_email')->nullable();
            $table->string('attendee_phone', 50)->nullable();
            $table->string('attendee_identity', 50)->nullable()->comment('NIK/KTP/Passport');
            $table->string('seat_number', 10)->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
            $table->unsignedBigInteger('checked_in_by')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('special_requirements')->nullable()->comment('Dietary, accessibility, etc.');
            $table->timestamps();

            // Indexes
            $table->index('ticket_code');
            $table->index('booking_id');
            $table->index('is_checked_in');
            $table->index('checked_in_by');
            $table->index('is_cancelled');
            $table->index(['booking_id', 'is_checked_in'], 'tickets_booking_checkin');

            // Foreign keys
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('checked_in_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
