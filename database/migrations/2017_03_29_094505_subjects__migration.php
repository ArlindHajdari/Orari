<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubjectsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject',50);
            $table->integer('ects');
            $table->tinyInteger('semester');
            $table->integer('subjecttype_id')->unsigned();

            $table->index('subjecttype_id');
            $table->foreign('subjecttype_id')->references('id')->on('subjecttypes');
            $table->engine ='InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['subjecttype_id']);
        });
    }
}
