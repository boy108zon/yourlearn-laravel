<?php

// database/migrations/xxxx_xx_xx_create_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the category (e.g., Electronics, Clothing)
            $table->text('description')->nullable();  // Category description
            $table->string('slug')->unique();  // Slug for SEO-friendly URLs
            $table->string('image_url')->nullable();  // Category image (optional)
            $table->boolean('is_active')->default(true);  // Active status of the category
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

