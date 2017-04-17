<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.15
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('log_id');
            $table->string('email');
            $table->string('password');
            $table->string('personal_number');
            $table->integer('cpa_id')->unsigned();
            $table->integer('academic_title_id')->unsigned();
            $table->string('photo');
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('email');
            $table->unique('log_id');
            $table->index('cpa_id');
            $table->index('academic_title_id');
            $table->foreign('cpa_id')->references('id')->on('cpas');
            $table->foreign('academic_title_id')->references('id')->on('academic_titles');
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('persistences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('code');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('slug');
        });

        DB::table('roles')->insert([
            ['slug'=>'admin', 'name' => 'Admin_SuAdmin'],
            ['slug'=>'user', 'name' => 'User_Mësimdhënës']
        ]);

        Schema::create('throttle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('type');
            $table->string('ip')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        DB::table('users')->insert([
            'email'=> 'arlind.hajdari@gmail.com',
            'password'=> bcrypt('admin'),
            'last_name'=> 'Hajdari',
            'first_name'=> 'Arlind',
            'personal_number' => '1111111111',
            'cpa_id' => 1,
            'academic_title_id'=>1,
            'photo'=>'Uploads/asdasd_12312.jpg',
            'log_id'=> '123'
        ]);

        DB::table('activations')->insert([
            'user_id'=>1,
            'code'=>'asuldigasygfhasa##!asdhiayg64987432',
            'completed'=>true
        ]);

        Schema::create('role_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->index('user_id');
            $table->index('role_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->primary(['user_id', 'role_id']);
        });

        DB::table('role_users')->insert([
            'user_id'=> 1,
            'role_id'=> 1
        ]);

        DB::table('activations')->insert([
            'user_id'=>1,
            'code'=>bcrypt('code'),
            'completed'=>1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
        Schema::dropIfExists('persistences');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('throttle');
        Schema::dropIfExists('users');

        Schema::table('activation',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('role_users',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('role_users',function(Blueprint $table){
            $table->dropForeign(['role_id']);
        });
        Schema::table('throttle',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('reminders',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('persistences',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
}
