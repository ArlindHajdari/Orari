<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $group
 * 
 * @property \App\Models\GroupsLushSubject $groups_lush_subject
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

	public function groups_lush_subject()
	{
		return $this->hasOne(\App\Models\GroupsLushSubject::class);
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
