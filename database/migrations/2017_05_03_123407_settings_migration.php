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
            $table->integer('max_hour_day_professor')->nullable();
            $table->integer('max_hour_day_assistant')->nullable();
            $table->integer('user_id')->unsigned();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->engine = 'InnoDB';
        });

        DB::table('settings')->insert([
            'start_winter_semester'=>Carbon::now()->year.'-10-03',
            'end_winter_semester'=>Carbon::now()->year.'-01-15',
            'start_summer_semester'=>Carbon::now()->year.'-02-15',
            'end_summer_semester'=>Carbon::now()->year.'-05-31',
            'user_id'=>1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
}
