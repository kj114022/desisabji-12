<?php
/**
 * File name: 2019_08_29_213921_create_payments_table.php
 * Last modified: 2020.04.30 at 06:25:41

 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->double('price', 8, 2)->default(0);
            $table->string('description', 255)->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('status')->nullable();
            $table->string('method')->nullable();
            $table->integer('order_id')->unsigned();
            $table->string('payer_id');
            $table->string('token');
            $table->string('source');
            $table->string('ip_address');
            $table->text('faliure_issue');
            $table->date('setllement_date');
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
        Schema::drop('payments');
    }
}
