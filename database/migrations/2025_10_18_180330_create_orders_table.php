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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->enum('order_type', ['login', 'manual'])->default('login');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->enum('pickup_method', ['pickup', 'delivery'])->default('pickup');
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', [
                'waiting_for_pickup',
                'picked_and_weighed', 
                'waiting_for_payment',
                'waiting_for_admin_verification',
                'processed',
                'completed'
            ])->default('waiting_for_pickup');
            $table->string('payment_proof')->nullable();
            $table->boolean('payment_verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('estimated_completion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
