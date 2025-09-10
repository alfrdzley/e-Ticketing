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
        // Add unique constraints and indexes to ULID columns
        Schema::table('events', function (Blueprint $table) {
            $table->unique('ulid');
            $table->index('ulid');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unique('ulid');
            $table->index('ulid');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unique('ulid');
            $table->index('ulid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['ulid']);
            $table->dropUnique(['ulid']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['ulid']);
            $table->dropUnique(['ulid']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['ulid']);
            $table->dropUnique(['ulid']);
        });
    }
};
