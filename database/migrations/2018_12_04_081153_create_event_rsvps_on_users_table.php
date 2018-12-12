<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventRsvpsOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_event', function (Blueprint $table) {
            $table->boolean('is_going')->nullable();
            $table->timestamp('responded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_event', function (Blueprint $table) {
            $table->dropColumn('is_going');
            $table->dropColumn('responded_at');
        });
    }
}
