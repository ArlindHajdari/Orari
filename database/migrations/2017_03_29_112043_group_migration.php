<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group');

            $table->engine = 'InnoDB';
        });

        DB::table('groups')->insert([
            ['group'=>'Gr. I'],
            ['group'=>'Gr. II'],
            ['group'=>'Gr. III'],
            ['group'=>'Gr. IV'],
            ['group'=>'Gr. V'],
            ['group'=>'Gr. VI'],
            ['group'=>'Gr. VII'],
            ['group'=>'Gr. VIII'],
            ['group'=>'Gr. IX'],
            ['group'=>'Gr. X'],
            ['group'=>'Gr. XI'],
            ['group'=>'Gr. XII'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
