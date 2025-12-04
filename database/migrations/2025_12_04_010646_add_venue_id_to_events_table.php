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
        Schema::table('events', function (Blueprint $table) {
            // Add venue_id foreign key after organizer_email
            $table->foreignId('venue_id')
                ->nullable()
                ->after('organizer_email')
                ->constrained('venues')
                ->nullOnDelete();

            // Index for faster queries
            $table->index('venue_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropIndex(['venue_id']);
            $table->dropColumn('venue_id');
        });
    }
};
