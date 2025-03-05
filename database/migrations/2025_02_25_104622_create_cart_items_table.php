<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade'); // Link to the cart
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Link to the product
            $table->integer('quantity')->default(1); // Quantity of the product
            $table->decimal('price', 10, 2); // Price of the product at the time it was added to the cart
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
