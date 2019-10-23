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
            $table->integer('department_id')->unsigned();

            $table->index('department_id');
            $table->index('subjecttype_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subjecttype_id')->references('id')->on('subjecttypes')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['subjecttype_id']);
        });

        Schema::dropIfExists('subjects');
    }
}
