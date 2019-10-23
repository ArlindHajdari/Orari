<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Faculty
 * 
 * @property int $id
 * @property string $faculty
 * @property int $academic_years
 * 
 * @property \Illuminate\Database\Eloquent\Collection $departments
 * @property \Illuminate\Database\Eloquent\Collection $halls
 *
 * @package App\Models
 */
class Faculty extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'academic_years' => 'int'
	];

	protected $fillable = [
		'faculty',
		'academic_years'
	];

	public function departments()
	{
		return $this->hasMany(\App\Models\Department::class);
	}

	public function halls()
	{
		return $this->hasMany(\App\Models\Hall::class, 'sec_faculty_id');
	}
}
