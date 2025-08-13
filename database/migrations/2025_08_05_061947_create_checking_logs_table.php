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
        Schema::create('checkin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->enum('action', ['check_in', 'check_out', 'manual_override']);
            $table->unsignedBigInteger('performed_by');
            $table->timestamp('performed_at');
            $table->string('location')->nullable()->comment('Check-in location (gate, entrance, etc.)');
            $table->string('device_info')->nullable()->comment('Scanner device information');
            $table->string('ip_address', 45)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('ticket_id');
            $table->index('performed_by');
            $table->index('performed_at');
            $table->index(['ticket_id', 'performed_at']);

            // Foreign keys
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_logs');
    }
};
