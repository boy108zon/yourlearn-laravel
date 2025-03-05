<?php

// database/migrations/xxxx_xx_xx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // User who placed the order
            $table->decimal('total_price', 10, 2);  // Total price of the order
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');  // Order status
            $table->string('shipping_address');  // Shipping address of the customer
            $table->string('billing_address');  // Billing address of the customer
            $table->string('payment_method')->nullable();  // Payment method used (e.g., Credit Card, PayPal)
            $table->string('tracking_number')->nullable();  // Tracking number (if applicable)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
