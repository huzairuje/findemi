<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_interest', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('interest_id');
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('interest_id')->references('id')->on('interests');
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
        Schema::dropIfExists('activity_interest');
    }
}
