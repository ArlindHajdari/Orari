<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcademicTitlesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_title');
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        DB::table('academic_titles')->insert([
            ['academic_title'=>'Dr.'],
            ['academic_title'=>'Prof.'],
            ['academic_title'=>'Sc.'],
            ['academic_title'=>'MSc.'],
            ['academic_title'=>'Ass.'],
            ['academic_title'=>'BSc.'],
            ['academic_title'=>'PHd.'],
            ['academic_title'=>'PHd.Can.'],
            ['academic_title'=>'Prof.Assoc.'],
            ['academic_title'=>'Doc.'],
            ['academic_title'=>'Dr.Sc.'],
            ['academic_title'=>'Prof.Ass'],
            ['academic_title'=>'Prof.Ass.Dr.'],
            ['academic_title'=>'Prof.Asoc.Dr.'],
            ['academic_title'=>'Mr.Sc.']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_titles');
    }
}
