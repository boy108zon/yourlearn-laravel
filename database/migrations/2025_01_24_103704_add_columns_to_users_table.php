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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('mobile')->nullable()->after('email');
            $table->string('profile_picture')->nullable()->after('mobile');
            $table->text('address')->nullable()->after('profile_picture');
            $table->string('country_id')->nullable()->after('address');
            $table->string('state_id')->nullable()->after('address');
            $table->string('city_id')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'mobile',
                'profile_picture',
                'address',
                'dob',
                'country_id',
                'state_id',
                'city_id'
            ]);
        });
    }
};
