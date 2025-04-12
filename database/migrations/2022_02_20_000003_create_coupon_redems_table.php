<?php
/**
 * File name: 2020_08_23_181022_create_coupons_table.php
 * Last modified: 2020.08.23 at 19:36:46

 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponRedemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_redems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code');
            $table->text('description')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('redem_amount')->nullable();
            $table->integer('user_id');
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
        Schema::drop('coupon_redems');
    }
}
