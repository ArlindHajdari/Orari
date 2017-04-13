<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class AcademicTitle
 * 
 * @property int $id
 * @property string $academic_title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class AcademicTitle extends Eloquent
{
	protected $fillable = [
		'academic_title'
	];

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
