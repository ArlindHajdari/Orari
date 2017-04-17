<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $group
 * 
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 *
 * @package App\Models
 */
class Group extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'group'
	];

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
