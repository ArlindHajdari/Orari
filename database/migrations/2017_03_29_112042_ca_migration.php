<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('ca', function (Blueprint $table) {
            $table->integer('cps_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->index('cps_id');
            $table->index('user_id');
            $table->foreign('cps_id')->references('id')->on('cps');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('ca');

        Schema::table('ca',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('ca',function(Blueprint $table){
            $table->dropForeign(['cps_id']);
        });
    }
}
