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
       // Adding the is_active column to the pivot table
       Schema::table('permission_role', function (Blueprint $table) {
        $table->boolean('is_active')->default(true); // Set default to true (active)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
