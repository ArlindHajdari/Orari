<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
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
		return $this->hasMany(\App\Models\Subject::class);
	}
}
