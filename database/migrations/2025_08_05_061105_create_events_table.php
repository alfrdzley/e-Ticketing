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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->text('description');
            $table->string('banner_image_url', 500)->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('location', 500);
            $table->text('address')->nullable()->comment('Full address');
            $table->decimal('price', 15, 2)->default(0.00);
            $table->unsignedInteger('quota')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('refund_policy')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('meta_title')->nullable()->comment('SEO title');
            $table->text('meta_description')->nullable()->comment('SEO description');
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('status');
            $table->index('start_date');
            $table->index(['status', 'start_date'], 'events_status_date');
            $table->index('category_id');
            $table->index('organizer_id');

            // Foreign keys (optional - can be enabled later)
            //             $table->foreign('category_id')->references('id')->on('event_categories')->onDelete('set null');
            //             $table->foreign('organizer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
