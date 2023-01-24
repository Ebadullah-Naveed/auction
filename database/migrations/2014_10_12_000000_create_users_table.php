<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id');
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->string('username',50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile_number',20)->unique();

            $table->string('cnic',20)->unique()->nullable();
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();

            $table->string('password');
            $table->string('language',20)->nullable();
            $table->string('image')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('otp',10)->nullable();

            $table->date('date_joined')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('last_login_ip',20)->nullable();

            $table->boolean('status')->default(0);
            $table->boolean('is_email_verified')->default(0);
            $table->boolean('is_phone_verified')->default(0);
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
};
