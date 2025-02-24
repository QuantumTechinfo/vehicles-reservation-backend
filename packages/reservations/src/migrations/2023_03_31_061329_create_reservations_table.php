<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('from_location'); // Starting location
            $table->string('to_location'); // Destination location
            $table->dateTime('start_time'); // Start datetime of the ride
            $table->dateTime('end_time')->nullable(); // End datetime (if applicable)
            $table->enum('ride_option', ['shared', 'entire_cabin']); // Ride option
            $table->string('client_name'); // Client's name
            $table->string('client_phone'); // Client's phone number
            $table->string('client_email'); // Client's email
            $table->text('description')->nullable(); // Optional notes
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Default is 'pending'
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
