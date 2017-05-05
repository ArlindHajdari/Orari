<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CpaMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cpa');

            $table->engine = 'InnoDB';
        });

        DB::table('cpas')->insert([
            'cpa'=> 'Profesor'
        ],[
            'cpa'=> 'Asistent'
        ],[
            'cpa'=> 'Ligjërues'
        ],[
            'cpa'=> 'Dekan'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpas');
    }
}
