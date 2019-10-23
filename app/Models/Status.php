<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Status
 * 
 * @property int $id
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $academic_titles
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Status extends Eloquent
{
	protected $table = 'status';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function academic_titles()
	{
		return $this->belongsToMany(\App\Models\AcademicTitle::class, 'status_academic_titles')
					->withPivot('normal_hours', 'extra_hours');
	}

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
