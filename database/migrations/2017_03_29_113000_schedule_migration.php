<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScheduleMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groups',50);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('day',20);
            $table->integer('cps_id')->unsigned();
            $table->integer('hall_id')->unsigned();
            $table->integer('lush_id')->unsigned();
            $table->integer('department_id')->unsigned();

            $table->index('cps_id');
            $table->index('hall_id');
            $table->index('lush_id');
            $table->index('department_id');
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
        Schema::dropIfExists('schedule');
    }
}
