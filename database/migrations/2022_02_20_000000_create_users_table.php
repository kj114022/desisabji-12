<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('mobile')->unique();
            $table->string('login_id');
            $table->string('customer_id');
            $table->string('email');
            $table->string('password');
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('landmark')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude', 24)->nullable();
            $table->string('longitude', 24)->nullable();
            $table->unsignedInteger('login_fail_count')->nullable()->default(0);
            $table->unsignedInteger('status')->nullable()->default(0);            
            $table->boolean('email_verified')->nullable()->default(0);
            $table->boolean('mobile_verified')->nullable()->default(0); 
            $table->string('mobile_otp')->nullable();
            $table->string('email_otp')->nullable();
            $table->string('password_hash')->nullable();
            $table->string('email_hash')->nullable();
            $table->string('activation_hash')->nullable();
            $table->string('reference_no')->nullable();
            $table->timestamp('email_expires_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('user_type')->nullable();
            $table->text('profile_image')->nullable();
            $table->boolean('is_admin')->nullable()->default(0); 
            $table->char('api_token', 60)->unique()->nullable()->default(null);
            $table->string('device_token')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('braintree_id')->nullable();
            $table->string('paypal_email')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
