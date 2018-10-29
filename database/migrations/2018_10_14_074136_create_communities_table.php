<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image_banner_url')->nullable();
            $table->string('tag')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->bigInteger('created_by');
            $table->float('lat')->nullable();
            $table->float('lon')->nullable();
            $table->string('address_from_map')->nullable();
            $table->string('base_camp_address')->nullable();
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
        Schema::dropIfExists('communities');
    }
}
