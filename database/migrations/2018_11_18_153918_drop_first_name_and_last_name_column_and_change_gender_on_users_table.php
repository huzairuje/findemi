<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFirstNameAndLastNameColumnAndChangeGenderOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * dropping existing column
         * wrong choice
         */
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });

        /**
         * create another column for the right choice
         */
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
