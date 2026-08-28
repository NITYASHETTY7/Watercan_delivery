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
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('sso_token')->nullable();
            $table->string('broadcast_token')->nullable();
            $table->string('timezone')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->longText('preferences')->nullable()->comment('User preferences');
            $table->text('bio')->nullable();
            $table->boolean('is_verified')->default(0)->nullable();
            $table->double('wallet')->default(0);
            $table->string('avatar')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('status')->default(1);
            $table->integer('delegate_access');
            $table->integer('temp_otp');
            $table->integer('google_id')->unsigned()->nullable();
            $table->text('google2fa_secret')->nullable()->comment('Google2fa Key');
            $table->integer('theme_id')->default(1);
            $table->softDeletes();
            $table->integer('is_online')->default(1);
            $table->longText('permissions')->nullable();
            $table->longText('setting_payload')->nullable();
            $table->timestamp('last_seen')->nullable(); // Fix applied here
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
