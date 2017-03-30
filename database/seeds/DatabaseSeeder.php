<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\AcademicTitle::class, 15)->create();
        factory(\App\Models\User::class, 14)->create();
    }
}
