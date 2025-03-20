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
        Schema::table('carts', function (Blueprint $table) {
            // Add new columns
            $table->string('applied_discount_type')->nullable(); // or use 'string' or 'enum' depending on your need
            $table->decimal('applied_discount', 8, 2)->nullable(); // Adjust decimal precision and scale as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop the columns if the migration is rolled back
            $table->dropColumn(['applied_discount_type', 'applied_discount']);
        });
    }
};
