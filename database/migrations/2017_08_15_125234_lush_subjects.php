<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LushSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_lush', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->integer('lush_id')->unsigned();

            $table->index('subject_id');
            $table->index('lush_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('lush_id')->references('id')->on('lush')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('subject_lush', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
        });

        Schema::table('subject_lush', function (Blueprint $table) {
            $table->dropForeign(['lush_id']);
        });

        Schema::dropIfExists('subject_lush');
    }
}
