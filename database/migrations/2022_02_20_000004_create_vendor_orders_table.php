<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');
            $table->integer('user_id')->unsigned();
            $table->integer('order_status_id')->unsigned();
            $table->double('order_total', 5, 2)->nullable()->default(0);
            $table->double('order_discount', 5, 2)->nullable()->default(0);
            $table->double('tax', 5, 2)->nullable()->default(0);
            $table->double('delivery_fee', 5, 2)->nullable()->default(0);
            $table->text('hint')->nullable();
            $table->boolean('active')->default(1); // added
            $table->integer('driver_id')->nullable()->unsigned();
            $table->integer('market_id')->nullable()->unsigned();
            $table->text('delivery_address');
            $table->integer('payment_id')->nullable()->unsigned();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null')->onUpdate('set null');
     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_orders');
    }
}
