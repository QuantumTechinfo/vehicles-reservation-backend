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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'user', 'vehicle_owner'])->default('user');
            $table->string('image')->nullable();
            $table->string('phone_no')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            $table->string('fcm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
