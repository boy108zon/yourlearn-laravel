<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodesTable extends Migration
{
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id(); 
            $table->string('code')->unique();
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_amount', 8, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date'); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_codes'); 
    }
}
