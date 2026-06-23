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
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'customer', 'restaurant', 'delivery'])->default('customer');
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('pincode')->nullable();
    $table->string('profile_image')->nullable();
    $table->boolean('is_active')->default(1);
    $table->boolean('is_approved')->default(0);
    $table->timestamp('email_verified_at')->nullable();
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
