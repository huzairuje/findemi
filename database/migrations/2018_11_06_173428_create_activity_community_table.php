<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityCommunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_community', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('activity_id');
                $table->bigInteger('community_id');
                $table->foreign('activity_id')->references('id')->on('activities');
                $table->foreign('community_id')->references('id')->on('communities');
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
        Schema::dropIfExists('activity_community');
    }
}
