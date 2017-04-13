<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 08 Apr 2017 14:07:49 +0000.
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
}
