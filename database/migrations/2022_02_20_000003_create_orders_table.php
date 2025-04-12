<?php
/**
 * File name: 2019_08_31_111104_create_orders_table.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('order_status_id')->unsigned();
            $table->double('tax', 5, 2)->nullable()->default(0);
            $table->double('delivery_fee', 5, 2)->nullable()->default(0);
            $table->text('hint')->nullable();
            $table->boolean('active')->default(1); // added
            $table->integer('driver_id')->nullable()->unsigned();
            $table->integer('delivery_address_id')->nullable()->unsigned();
            $table->integer('payment_id')->nullable()->unsigned();
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
        Schema::drop('orders');
    }
}
