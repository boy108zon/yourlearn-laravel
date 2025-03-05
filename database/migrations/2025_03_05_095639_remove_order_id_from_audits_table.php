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
        // Remove the `order_id` column from the `audits` table
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('order_id');  // Remove the incorrect order_id column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->integer('order_id')->nullable();  // Add the `order_id` column back
        });
    }
};
