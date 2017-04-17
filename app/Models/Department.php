<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Department
 * 
 * @property int $id
 * @property string $department
 * @property int $faculty_id
 * 
 * @property \App\Models\Faculty $faculty
 * @property \Illuminate\Database\Eloquent\Collection $subjects
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 *
 * @package App\Models
 */
class Department extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'faculty_id' => 'int'
	];

	protected $fillable = [
		'department',
		'faculty_id'
	];

	public function faculty()
	{
		return $this->belongsTo(\App\Models\Faculty::class);
	}

	public function subjects()
	{
		return $this->belongsToMany(\App\Models\Subject::class, 'department_subjects');
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
