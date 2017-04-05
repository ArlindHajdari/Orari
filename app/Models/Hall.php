<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
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
 * 
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
		'halltype_id' => 'int'
	];

	protected $fillable = [
		'hall',
		'capacity',
		'halltype_id'
	];

	public function halltype()
	{
		return $this->belongsTo(\App\Models\Halltype::class);
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
