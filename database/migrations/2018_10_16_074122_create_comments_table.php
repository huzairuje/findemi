<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('post_id');
            $table->bigInteger('parent_id')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('parent_id')->references('id')->on('comments');
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
        Schema::dropIfExists('comments');
    }
}
