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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');         // Menu title
            $table->string('url');           // URL for the menu link
            $table->string('slug')->unique(); // Slug for the menu (unique)
            $table->integer('sequence')->default(0); // Sequence/order of the menu
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status of the menu
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade'); // Parent menu ID
            $table->string('icon')->nullable(); // Bootstrap icon class (or URL for custom icons)
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
