<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department',150);
            $table->integer('faculty_id')->unsigned();

            $table->index('faculty_id');
            $table->foreign('faculty_id')->references('id')->on('faculties');
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
        Schema::dropIfExists('departments');
        Schema::table('departments', function(Blueprint $table){
            $table->dropForeign(['faculty_id']);
        });
    }
}
