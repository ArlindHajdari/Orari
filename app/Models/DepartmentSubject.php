<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DepartmentSubject
 * 
 * @property int $department_id
 * @property int $subject_id
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
}
