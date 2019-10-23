<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Lush
 * 
 * @property int $id
 * @property string $lush
 * 
 * @property \Illuminate\Database\Eloquent\Collection $cpas
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 * @property \Illuminate\Database\Eloquent\Collection $subjects
 *
 * @package App\Models
 */
class Lush extends Eloquent
{
	protected $table = 'lush';
	public $timestamps = false;

	protected $fillable = [
		'lush'
	];

	public function cpas()
	{
		return $this->belongsToMany(\App\Models\Cpa::class, 'lush_cpa');
	}

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}

	public function subjects()
	{
		return $this->belongsToMany(\App\Models\Subject::class, 'subject_lush')
					->withPivot('id');
	}
}
