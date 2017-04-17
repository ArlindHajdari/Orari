<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DepartmentSubject
 * 
 * @property int $department_id
 * @property int $subject_id
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Subject $subject
 *
 * @package App\Models
 */
class DepartmentSubject extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'department_id' => 'int',
		'subject_id' => 'int'
	];

	protected $fillable = [
		'department_id',
		'subject_id'
	];

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function subject()
	{
		return $this->belongsTo(\App\Models\Subject::class);
	}
}
