<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentSubjectsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('department_subjects', function (Blueprint $table) {
            $table->integer('department_id')->unsigned();
            $table->integer('subject_id')->unsigned();

            $table->index('department_id');
            $table->index('subject_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('subject_id')->references('id')->on('subjects');
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
        Schema::dropIfExists('department_subjects');

        Schema::table('department_subjects', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('department_subjects', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
        });
    }
}
