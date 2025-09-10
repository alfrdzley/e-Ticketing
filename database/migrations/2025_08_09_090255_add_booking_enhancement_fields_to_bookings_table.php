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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('ticket_qr_code_path')->nullable()->after('notes');
            $table->string('ticket_pdf_path')->nullable()->after('ticket_qr_code_path');
            $table->timestamp('entry_validated_at')->nullable()->after('ticket_pdf_path');
            $table->string('payment_proof_path')->nullable()->after('entry_validated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'ticket_qr_code_path',
                'ticket_pdf_path',
                'entry_validated_at',
                'payment_proof_path',
            ]);
        });
    }
};
