<?php

// database/migrations/xxxx_xx_xx_create_category_product_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProductTable extends Migration
{
    public function up()
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_id', 'category_id']);  // Ensure unique product-category pairs
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_product');
    }
}
