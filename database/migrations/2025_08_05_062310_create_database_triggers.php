<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Note: Laravel doesn't have native support for triggers in migrations
        // These need to be executed as raw DB statements
        // Skip triggers for SQLite (testing environment)

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Trigger for auto-generating booking codes
        DB::unprepared('
            CREATE TRIGGER generate_booking_code
            BEFORE INSERT ON bookings
            FOR EACH ROW
            BEGIN
                IF NEW.booking_code IS NULL OR NEW.booking_code = "" THEN
                    SET NEW.booking_code = CONCAT("BK-", LPAD(NEW.id, 8, "0"));
                END IF;
            END
        ');

        // Trigger for updating discount code usage
        DB::unprepared('
            CREATE TRIGGER update_discount_usage
            AFTER UPDATE ON bookings
            FOR EACH ROW
            BEGIN
                IF OLD.status != "paid" AND NEW.status = "paid" AND NEW.discount_code_id IS NOT NULL THEN
                    UPDATE discount_codes
                    SET used_count = used_count + 1
                    WHERE id = NEW.discount_code_id;
                END IF;
            END
        ');

        // Trigger for auto-generating ticket codes
        DB::unprepared('
            CREATE TRIGGER generate_ticket_code
            BEFORE INSERT ON tickets
            FOR EACH ROW
            BEGIN
                IF NEW.ticket_code IS NULL OR NEW.ticket_code = "" THEN
                    SET NEW.ticket_code = CONCAT("TKT-", UPPER(SUBSTRING(MD5(RAND()), 1, 8)));
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS generate_booking_code');
        DB::unprepared('DROP TRIGGER IF EXISTS update_discount_usage');
        DB::unprepared('DROP TRIGGER IF EXISTS generate_ticket_code');
    }
};
