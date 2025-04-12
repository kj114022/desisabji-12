<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_city_id');
            $table->string('vendor_id');
            $table->string('product_name');
            $table->double('actual_price', 8, 2)->nullable()->default(0);          
            $table->double('market_price', 8, 2)->nullable()->default(0);
            $table->double('whole_sale_price', 8, 2)->nullable()->default(0);
            $table->integer('bulk_qty')->unsigned();
            $table->boolean('deliverable')->nullable()->default(1); // added
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
        Schema::dropIfExists('daily_prices');
    }
}
