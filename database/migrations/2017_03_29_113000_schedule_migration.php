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
        Schema::enableForeignKeyConstraints();

        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('user_id')->unsigned();
            $table->integer('hall_id')->unsigned();
            $table->integer('lush_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('group_id')->nullable()->unsigned();
            $table->date('from');
            $table->date('to');
            $table->boolean('to_be_deleted')->default(0);

            $table->index('user_id');
            $table->index('hall_id');
            $table->index('lush_id');
            $table->index('group_id');
            $table->index('subject_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('lush_id')->references('id')->on('lush')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('schedule',function(Blueprint $table){
            $table->dropForeign(['department_id']);
        });

        Schema::table('schedule',function(Blueprint $table){
            $table->dropForeign(['cps_id']);
        });

        Schema::table('schedule',function(Blueprint $table){
            $table->dropForeign(['lush_id']);
        });

        Schema::table('schedule',function(Blueprint $table){
            $table->dropForeign(['hall_id']);
        });

        Schema::dropIfExists('schedule');
    }
}
