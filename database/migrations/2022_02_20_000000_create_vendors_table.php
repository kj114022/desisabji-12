<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('mobile')->unique();
            $table->string('login_id')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->char('api_token', 60)->unique()->nullable()->default(null);
            $table->string('device_token')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('landmark')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude', 24)->nullable();
            $table->string('longitude', 24)->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('adhar_no')->nullable();
            $table->string('company_reg_no')->nullable();
            $table->text('other_details')->nullable();
            $table->unsignedInteger('login_fail_count')->nullable()->default(0);
            $table->unsignedInteger('status')->nullable()->default(0);            
            $table->boolean('email_verified')->nullable()->default(0);
            $table->boolean('mobile_verified')->nullable()->default(0); 
            $table->string('mobile_otp')->nullable();
            $table->string('email_otp')->nullable();
            $table->string('password_hash')->nullable();
            $table->string('email_hash')->nullable();
            $table->timestamp('email_expires_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('vendor_type')->nullable();
            $table->text('profile_image')->nullable();
            $table->boolean('is_admin')->nullable()->default(0); 
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
        Schema::dropIfExists('vendors');
    }
}
