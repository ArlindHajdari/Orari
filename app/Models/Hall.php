<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Hall
 * 
 * @property int $id
 * @property string $hall
 * @property int $capacity
 * @property int $halltype_id
 * @property int $faculty_id
 * @property int $sec_faculty_id
 * 
 * @property \App\Models\Faculty $faculty
 * @property \App\Models\Halltype $halltype
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 *
 * @package App\Models
 */
class Hall extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'capacity' => 'int',
		'halltype_id' => 'int',
		'faculty_id' => 'int',
		'sec_faculty_id' => 'int'
	];

	protected $fillable = [
		'hall',
		'capacity',
		'halltype_id',
		'faculty_id',
		'sec_faculty_id'
	];

	public function faculty()
	{
		return $this->belongsTo(\App\Models\Faculty::class, 'sec_faculty_id');
	}

	public function halltype()
	{
		return $this->belongsTo(\App\Models\Halltype::class);
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
