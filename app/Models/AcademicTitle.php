<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:30 +0000.
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
 * @property \Illuminate\Database\Eloquent\Collection $statuses
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class AcademicTitle extends Eloquent
{
	protected $fillable = [
		'academic_title'
	];

	public function statuses()
	{
		return $this->belongsToMany(\App\Models\Status::class, 'status_academic_titles')
					->withPivot('normal_hours', 'extra_hours');
	}

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
