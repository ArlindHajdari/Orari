<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvailabilityMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::enableForeignKeyConstraints();

        Schema::create('availability', function (Blueprint $table) {
            $table->increments('id');
            $table->time('TimeFrom');
            $table->time('TimeTo');
            $table->integer('user_id')->unsigned();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availability');
        Schema::table('availability', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
}
