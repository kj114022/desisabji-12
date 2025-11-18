<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sign_up_audits', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable()->comment('ID of the user who signed up');
            $table->string('ip_address')->nullable()->comment('IP address of the user during sign up');
            $table->string('device_info')->nullable()->comment('Device information of the user during sign up');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sign_up_audits');
    }
};
