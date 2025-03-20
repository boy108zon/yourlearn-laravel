<?php

// In create_promo_code_product_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodeProductTable extends Migration
{
    public function up()
    {
        Schema::create('promo_code_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id') // Foreign key for promo_codes table
                  ->constrained('promo_codes')  // Reference to promo_codes table
                  ->onDelete('cascade'); // Delete this record if the promo_code is deleted
            $table->foreignId('product_id') // Foreign key for products table
                  ->constrained('products') // Reference to products table
                  ->onDelete('cascade'); // Delete this record if the product is deleted
            $table->timestamps(); // To track when the relationship was created or updated
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_code_product');
    }
}
