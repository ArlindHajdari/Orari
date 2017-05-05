<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubjectTypesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('subjecttypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subjecttype',50);

            $table->engine = 'InnoDB';
        });

        DB::table('subjecttypes')->insert([[
            'subjecttype'=>'Obligative',
        ],[
            'subjecttype'=>'Zgjedhore'
        ]]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjecttypes');
    }
}
