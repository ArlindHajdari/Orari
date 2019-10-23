<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class InsertsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert([
            ['slug'=>'admin', 'name' => 'Admin_SuAdmin'],
            ['slug'=>'user', 'name' => 'User_Mësimdhënës']
        ]);

        DB::table('faculties')->insert([
            ['faculty'=>'Shkenca Kompjuterike','academic_years'=>3],
            ['faculty'=>'Edukim','academic_years'=>3],
            ['faculty'=>'Juridik','academic_years'=>4],
            ['faculty'=>'Ekonomik','academic_years'=>3]
        ]);

        DB::table('departments')->insert([
            ['department'=>'Përgjithshëm','faculty_id'=>1],
            ['department'=>'SEW','faculty_id'=>1],
            ['department'=>'IE','faculty_id'=>1],
            ['department'=>'Përgjithshëm','faculty_id'=>4],
            ['department'=>'Banka dhe financa','faculty_id'=>4],
            ['department'=>'Menaxhment dhe informatik','faculty_id'=>4],
            ['department'=>'Marketing','faculty_id'=>4],
            ['department'=>'Fillor','faculty_id'=>2],
            ['department'=>'Parafillor','faculty_id'=>2],
            ['department'=>'Përgjithshëm','faculty_id'=>3],
            ['department'=>'E drejta penale','faculty_id'=>3],
            ['department'=>'E drejta civile','faculty_id'=>3]
        ]);

        DB::table('academic_titles')->insert(['academic_title'=>'Prof.Dr.']);

        DB::table('halltypes')->insert([[
            'hallType'=>'Ligjëratë'
        ],[
            'hallType'=>'Kabinet'
        ]]);

        DB::table('subjecttypes')->insert([[
            'subjecttype'=>'Obligative',
        ],[
            'subjecttype'=>'Zgjedhore'
        ]]);

        DB::table('status')->insert([
            [
                'name'=>'Rregullt'
            ],[
                'name'=>'Angazhuar'
            ]
        ]);

        DB::table('cpas')->insert([[
            'cpa'=> 'Profesor'
        ],[
            'cpa'=> 'Asistent'
        ],[
            'cpa'=> 'Ligjërues'
        ],[
            'cpa'=> 'Dekan'
        ]]);

        DB::table('users')->insert([
            'email'=> 'arlind.hajdari@gmail.com',
            'password'=> bcrypt('admin'),
            'last_name'=> 'Hajdari',
            'first_name'=> 'Arlind',
            'personal_number' => '1111111111',
            'cpa_id' => 1,
            'academic_title_id'=>1,
            'status_id'=>1,
            'photo'=>'Uploads/asdasd_12312.jpg',
            'log_id'=> '123'
        ]);

        DB::table('activations')->insert([
            'user_id'=>1,
            'code'=>'asuldigasygfhasa##!asdhiayg64987432',
            'completed'=>true
        ]);

        DB::table('role_users')->insert([
            'user_id'=> 1,
            'role_id'=> 1
        ]);

        DB::table('activations')->insert([
            'user_id'=>1,
            'code'=>bcrypt('code'),
            'completed'=>1
        ]);

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

        DB::table('settings')->insert([
            'start_winter_semester'=>Carbon::now()->year.'-10-03',
            'end_winter_semester'=>(Carbon::now()->year+1).'-01-15',
            'start_summer_semester'=>(Carbon::now()->year+1).'-02-15',
            'end_summer_semester'=>(Carbon::now()->year+1).'-05-31',
            'start_schedule_time'=>Carbon::parse('08:00')->toTimeString(),
            'end_schedule_time'=>Carbon::parse('20:00')->toTimeString(),
            'max_hour_day_lecture'=>3,
            'max_hour_day_exercise'=>6,
            'user_id'=>1
        ]);

        DB::table('lush')->insert([[
            'lush'=>'Ligjëratë',
        ],[
            'lush'=>'Ushtrime'
        ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
