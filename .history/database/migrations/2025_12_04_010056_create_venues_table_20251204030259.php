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
        Schema::create('venues', function (Blueprint $table) {
            // Primary Key
            $table->id();
            $table->uuid('uuid')->unique();

            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Location
            $table->string('address');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('country', 2)->default('TR');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Capacity
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('standing_capacity')->nullable();
            $table->unsignedInteger('seating_capacity')->nullable();

            // Features
            $table->json('amenities')->nullable(); // ["parking", "wifi", "wheelchair_access"]
            $table->json('images')->nullable();
            $table->string('cover_image')->nullable();

            // Owner
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('city');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
