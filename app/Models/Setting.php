<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

/**
 * Class Setting
 *
 * @property int $id
 * @property \Carbon\Carbon $start_summer_semester
 * @property \Carbon\Carbon $end_summer_semester
 * @property \Carbon\Carbon $start_winter_semester
 * @property \Carbon\Carbon $end_winter_semester
 * @property int $max_hour_day_lecture
 * @property int $max_hour_day_exercise
 * @property int $user_id
 *
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Setting extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'max_hour_day_lecture' => 'int',
		'max_hour_day_exercise' => 'int',
		'user_id' => 'int'
	];

	protected $dates = [
		'start_summer_semester',
		'end_summer_semester',
		'start_winter_semester',
		'end_winter_semester'
	];

	protected $fillable = [
		'start_summer_semester',
		'end_summer_semester',
		'start_winter_semester',
		'end_winter_semester',
		'max_hour_day_lecture',
		'max_hour_day_exercise',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function getStartSummerSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getEndSummerSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getStartWinterSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getEndWinterSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}
}
