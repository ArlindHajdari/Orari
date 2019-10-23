<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Schedule
 *
 * @property int $id
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property int $user_id
 * @property int $hall_id
 * @property int $lush_id
 * @property int $subject_id
 * @property int $group_id
 * @property \Carbon\Carbon $from
 * @property \Carbon\Carbon $to
 *
 * @property \App\Models\Group $group
 * @property \App\Models\Hall $hall
 * @property \App\Models\Lush $lush
 * @property \App\Models\Subject $subject
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Schedule extends Eloquent
{
	protected $table = 'schedule';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'hall_id' => 'int',
		'lush_id' => 'int',
		'subject_id' => 'int',
		'group_id' => 'int'
	];

	// protected $dates = [
	// 	'start',
	// 	'end',
	// 	'from',
	// 	'to'
	// ];

	protected $fillable = [
		'start',
		'end',
		'user_id',
		'hall_id',
		'lush_id',
		'subject_id',
		'group_id',
		'from',
		'to'
	];

	public function group()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function hall()
	{
		return $this->belongsTo(\App\Models\Hall::class);
	}

	public function lush()
	{
		return $this->belongsTo(\App\Models\Lush::class);
	}

	public function subject()
	{
		return $this->belongsTo(\App\Models\Subject::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
