<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_registraions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('mobile')->unique();
            $table->string('email')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('landmark')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude', 24)->nullable();
            $table->string('longitude', 24)->nullable();
            $table->unsignedInteger('status')->nullable()->default(0);            
            $table->boolean('email_verified')->nullable()->default(0);
            $table->boolean('mobile_verified')->nullable()->default(0); 
            $table->string('mobile_otp')->nullable();
            $table->string('email_otp')->nullable();
            $table->string('password_hash')->nullable();
            $table->string('reference_no')->nullable();
            $table->timestamp('email_expires_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('user_type')->nullable();
            $table->char('api_token', 60)->unique()->nullable()->default(null);
            $table->string('device_token')->nullable();
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
        Schema::dropIfExists('user_registraions');
    }
}
