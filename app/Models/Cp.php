<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cp
 * 
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * 
 * @property \App\Models\Subject $subject
 * @property \App\Models\User $user
 * @property \App\Models\Ca $ca
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 *
 * @package App\Models
 */
class Cp extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'subject_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'subject_id'
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

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class, 'cps_id');
	}
}
