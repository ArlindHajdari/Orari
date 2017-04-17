<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HallsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('halls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hall',50);
            $table->tinyInteger('capacity');
            $table->integer('halltype_id')->unsigned();
            $table->integer('faculty_id')->unsigned();

            $table->engine = 'InnoDB';
            $table->index('halltype_id');
            $table->index('faculty_id');
            $table->foreign('halltype_id')->references('id')->on('halltypes');
            $table->foreign('faculty_id')->references('id')->on('faculties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halls', function(Blueprint $table){
            $table->dropForeign(['halltype_id']);
            $table->dropForeign(['faculty_id']);
        });
        Schema::dropIfExists('halls');
    }
}
