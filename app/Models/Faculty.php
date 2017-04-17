<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Faculty
 * 
 * @property int $id
 * @property string $faculty
 * 
 * @property \Illuminate\Database\Eloquent\Collection $departments
 * @property \Illuminate\Database\Eloquent\Collection $halls
 *
 * @package App\Models
 */
class Faculty extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'faculty'
	];

	public function departments()
	{
		return $this->hasMany(\App\Models\Department::class);
	}

	public function halls()
	{
		return $this->hasMany(\App\Models\Hall::class);
	}
}
