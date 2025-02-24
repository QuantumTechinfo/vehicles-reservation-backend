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
        Schema::create('reservation_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('vehicle_id')->nullable(); // Make vehicle_id nullable
            $table->integer('no_of_days');
            $table->decimal('rate', 10, 2); // Per day rate
            $table->decimal('total_amount', 15, 2);
            $table->decimal('commission', 10, 2); // Owner's commission
            $table->date('entry_date');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->nullable(); // Add nullable to foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_histories');
    }
};
