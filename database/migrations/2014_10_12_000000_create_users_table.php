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
            $table->bigIncrements('id');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('location')->nullable();
            $table->string('auth_key')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->date('birthday')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_user_id')->nullable();
            $table->string('display_name_social')->nullable();
            $table->string('profile_url')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('gender')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_show_interest')->default(true);
            $table->boolean('is_show_community')->default(true);
            $table->boolean('is_show_reminder')->default(false);
            $table->string('fcm_token')->nullable();
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
