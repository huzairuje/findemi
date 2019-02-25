<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnTypeOnAllModul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        Schema::table('communities', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('communities', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('type');
        });

    }
}
