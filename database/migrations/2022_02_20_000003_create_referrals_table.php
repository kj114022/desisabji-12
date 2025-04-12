<?php
/**
 * File name: 2020_08_23_181022_create_coupons_table.php
 * Last modified: 2020.08.23 at 19:36:46

 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referral_no');
            $table->integer('referral_amt')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('redem_amount')->nullable();
            $table->date('redem_date')->nullable();
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
        Schema::drop('referrals');
    }
}
