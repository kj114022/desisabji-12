<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryAddressesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('delivery_addresses');
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('landmark')->nullable();
            $table->string('latitude', 24)->nullable();
            $table->string('longitude', 24)->nullable();
            $table->boolean('is_default')->nullable();
            $table->boolean('is_billing')->nullable();
			$table->boolean('status')->default(1);
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delivery_addresses');
    }
}
