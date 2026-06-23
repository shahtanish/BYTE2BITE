<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
            $table->string('food_name'); // snapshot of name at time of order
            $table->decimal('food_price', 10, 2); // snapshot of price
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });

        // Order Restaurant Status (per-restaurant status in a multi-vendor order)
        Schema::create('order_restaurant_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'preparing', 'ready', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamps();
        });

        // Delivery Partner Profile
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vehicle_type')->nullable(); // bike, cycle, scooter
            $table->string('vehicle_number')->nullable();
            $table->string('license_number')->nullable();
            $table->string('id_proof_type')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->string('id_proof_image')->nullable();
            $table->decimal('earnings_total', 10, 2)->default(0);
            $table->integer('total_deliveries')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamps();
        });

        // Delivery Earnings Log
        Schema::create('delivery_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_partner_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->timestamp('earned_at')->nullable();
            $table->timestamps();
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_earnings');
        Schema::dropIfExists('order_restaurant_status');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('delivery_partners');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
