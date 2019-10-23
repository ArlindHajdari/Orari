<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatusAcademicTitles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_academic_titles', function (Blueprint $table) {
            $table->integer('academic_title_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('normal_hours')->nullable();
            $table->integer('extra_hours');

            $table->index('academic_title_id');
            $table->index('status_id');
            $table->foreign('academic_title_id')->references('id')->on('academic_titles');
            $table->foreign('status_id')->references('id')->on('status');
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
        Schema::table('status_academic_titles', function(Blueprint $table){
            $table->dropForeign(['academic_title_id']);
        });
        Schema::table('status_academic_titles', function(Blueprint $table){
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('status_cpas');
    }
}
