<?php

// database/migrations/xxxx_xx_xx_create_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the product
            $table->text('description');  // Product description
            $table->decimal('price', 8, 2);  // Price of the product
            $table->integer('stock_quantity')->default(0);  // Quantity available in stock
            $table->decimal('weight', 8, 2)->nullable();  // Weight of the product (useful for shipping)
            $table->string('sku')->unique();  // Stock Keeping Unit (SKU)
            $table->string('image_url')->nullable();  // Product image URL (for displaying images)
            $table->boolean('is_active')->default(true);  // Active status, if the product is available
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
