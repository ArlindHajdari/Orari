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
            'academic_title'=>'Dr.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Prof.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Sc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'MSc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Ass.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'BSc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'PHd.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'PHd.Can.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Prof.Assoc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Doc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Dr.Sc.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Prof.Ass'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Prof.Ass.Dr.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Prof.Asoc.Dr.'
        ]);

        DB::table('academic_titles')->insert([
            'academic_title'=>'Mr.Sc.'
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
