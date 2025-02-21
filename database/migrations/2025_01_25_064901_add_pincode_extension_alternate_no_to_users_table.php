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
        Schema::table('users', function (Blueprint $table) {
            $table->string('extension', 10)->nullable()->after('email');
            $table->string('alternate_no', 15)->nullable()->after('mobile');
            $table->string('pincode', 6)->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['extension', 'alternate_no', 'pincode']);
        });
    }
};
