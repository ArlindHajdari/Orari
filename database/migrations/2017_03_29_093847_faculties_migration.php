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
            $table->integer('academic_years');

            $table->engine = 'InnoDB';
        });

        DB::unprepared("
        CREATE TRIGGER `tr_addRoleFromFaculty` AFTER INSERT ON `faculties` FOR EACH ROW BEGIN
            INSERT INTO roles(name,slug) VALUES(concat('Dekan_',NEW.faculty), concat('dekan_',NEW.faculty));
        END
        ");

        DB::unprepared("
        CREATE TRIGGER `tr_deleteRoleFromFaculty` AFTER DELETE ON `faculties` FOR EACH ROW BEGIN
            DELETE FROM roles WHERE slug=concat('dekan_',OLD.faculty);
        END
        ");

        DB::unprepared("
        CREATE TRIGGER `tr_updateRoleFromFaculty` BEFORE UPDATE ON `faculties` FOR EACH ROW BEGIN
            UPDATE roles SET slug=concat('dekan_',NEW.faculty), name=concat('Dekan_',NEW.faculty) WHERE slug=concat('dekan_',OLD.faculty);
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
        DB::unprepared('DROP TRIGGER `tr_deleteRoleFromFaculty`');
        DB::unprepared('DROP TRIGGER `tr_updateRoleFromFaculty`');
    }
}
