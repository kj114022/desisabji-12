<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_city_id')->unsigned();
            $table->double('vendor_price', 8, 2)->nullable()->default(0);         
            $table->double('market_price', 8, 2)->nullable()->default(0);
            $table->double('whole_sale_price', 8, 2)->nullable()->default(0);
            $table->integer('bulk_qty')->unsigned();
            $table->boolean('active')->nullable()->default(1); // added
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
        Schema::dropIfExists('city_prices');
    }
}
