<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Subject
 *
 * @property int $id
 * @property string $subject
 * @property int $ects
 * @property int $semester
 * @property int $subjecttype_id
 * @property int $department_id
 *
 * @property \App\Models\Department $department
 * @property \App\Models\Subjecttype $subjecttype
 * @property \Illuminate\Database\Eloquent\Collection $cps
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 * @property \Illuminate\Database\Eloquent\Collection $lushes
 *
 * @package App\Models
 */
class Subject extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'ects' => 'int',
		'semester' => 'int',
		'subjecttype_id' => 'int',
		'department_id' => 'int'
	];

	protected $fillable = [
		'subject',
		'ects',
		'semester',
		'subjecttype_id',
		'department_id'
	];

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function subjecttype()
	{
		return $this->belongsTo(\App\Models\Subjecttype::class);
	}

	public function cps()
	{
		return $this->hasMany(\App\Models\Cp::class);
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}

	public function lushes()
	{
		return $this->belongsToMany(\App\Models\Lush::class, 'subject_lush')
					->withPivot('id');
	}
}
