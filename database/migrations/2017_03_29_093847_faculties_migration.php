<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacultiesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('faculty',150);

            $table->engine = 'InnoDB';
        });

        DB::unprepared("
        CREATE TRIGGER `tr_addRoleFromFaculty` AFTER INSERT ON `faculties` FOR EACH ROW BEGIN
            INSERT INTO roles(name,slug) VALUES(concat('Dekan_',NEW.faculty), concat('dekan_',NEW.faculty));
        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
        DB::unprepared('DROP TRIGGER `tr_addRoleFromFaculty`');
    }
}
