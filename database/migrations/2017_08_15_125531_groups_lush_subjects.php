<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupsLushSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_lushSubjects', function (Blueprint $table) {
            $table->integer('subject_lush_id')->unsigned();
            $table->integer('group_id')->unsigned();

            $table->index('subject_lush_id');
            $table->index('group_id');
            $table->foreign('subject_lush_id')->references('id')->on('subject_lush')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('groups_lush_subjects', function (Blueprint $table) {
            $table->dropForeign(['lush_subjects_id']);
        });

        Schema::table('groups_lush_subjects', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });

        Schema::dropIfExists('groups_lush_subjects');
    }
}
