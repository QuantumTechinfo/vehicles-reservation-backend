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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            // uploader id
            $table->foreignId('uploader_id')->constrained('users')->cascadeOnDelete();

            // Vehicle information
            $table->string('vehicle_name');
            $table->string('vehicle_number')->unique();
            $table->text('vehicle_description')->nullable();

            // Blue book image path
            $table->string('blue_book')->nullable();

            // JSON field to store driver details: e.g. [{ "contact_number": "...", "name": "...", "license_no": "..." }, ...]
            $table->json('drivers')->nullable();

            // JSON field to store multiple vehicle images (paths)
            $table->json('vehicle_images')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
