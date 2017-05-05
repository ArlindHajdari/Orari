<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatusCpas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_cpas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cpa_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('normal_hours');
            $table->integer('extra_hours')->nullable();

            $table->index('cpa_id');
            $table->index('status_id');
            $table->foreign('cpa_id')->references('id')->on('cpas');
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
        Schema::dropIfExists('status_cpas');
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['cpa_id']);
        });
        Schema::table('subjects', function(Blueprint $table){
            $table->dropForeign(['status_id']);
        });
    }
}
