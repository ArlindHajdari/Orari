<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cp
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property int $lecture_hours
 * @property int $exercise_hours
 *
 * @property \App\Models\Subject $subject
 * @property \App\Models\User $user
 * @property \App\Models\Ca $ca
 *
 * @package App\Models
 */
class Cp extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'subject_id' => 'int',
		'lecture_hours' => 'int',
		'exercise_hours' => 'int'
	];

	protected $fillable = [
		'user_id',
		'subject_id',
		'lecture_hours',
		'exercise_hours'
	];

	public function subject()
	{
		return $this->belongsTo(\App\Models\Subject::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function ca()
	{
		return $this->hasMany(\App\Models\Ca::class, 'cps_id');
	}
}
