<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->string('product_name');
            $table->double('actual_price', 8, 2)->nullable()->default(0);
            $table->double('discount_price', 8, 2)->nullable()->default(0);            
            $table->integer('discount')->unsigned();
            $table->integer('discount_type')->unsigned();
            $table->boolean('deliverable')->nullable()->default(1); // added
            $table->integer('city_id')->unsigned();
            $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_products');
    }
}
