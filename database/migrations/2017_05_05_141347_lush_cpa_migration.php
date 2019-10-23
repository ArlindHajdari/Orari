<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LushCpaMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lush_cpa', function (Blueprint $table) {
            $table->integer('cpa_id')->unsigned();
            $table->integer('lush_id')->unsigned();

            $table->index('cpa_id');
            $table->index('lush_id');
            $table->foreign('cpa_id')->references('id')->on('cpas')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('lush_cpa', function (Blueprint $table) {
            $table->dropForeign(['cpa_id']);
        });

        Schema::table('lush_cpa', function (Blueprint $table) {
            $table->dropForeign(['lush_id']);
        });

        Schema::dropIfExists('lush_cpa');
    }
}
