<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class SettingsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_summer_semester');
            $table->date('end_summer_semester');
            $table->date('start_winter_semester');
            $table->date('end_winter_semester');
            $table->time('start_schedule_time');
            $table->time('end_schedule_time');
            $table->integer('max_hour_day_lecture');
            $table->integer('max_hour_day_exercise');
            $table->integer('user_id')->unsigned();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('settings');
    }
}
