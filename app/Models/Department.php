<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
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
}
