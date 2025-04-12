<?php
/**
 * File name: 2020_08_23_181022_create_coupons_table.php
 * Last modified: 2020.08.23 at 19:36:46

 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletPaymentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wallet_id');
            $table->integer('user_id');
            $table->integer('payment_amt');
            $table->date('payment_date');
            $table->boolean('status')->default(1);
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
        Schema::drop('wallet_payments');
    }
}
