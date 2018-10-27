<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_interest', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('community_id');
            $table->bigInteger('interest_id');
            $table->foreign('community_id')->references('id')->on('communities');
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
        Schema::dropIfExists('community_interest');
    }
}
