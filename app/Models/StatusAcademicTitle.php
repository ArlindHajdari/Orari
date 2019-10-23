<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class StatusAcademicTitle
 * 
 * @property int $academic_title_id
 * @property int $status_id
 * @property int $normal_hours
 * @property int $extra_hours
 * 
 * @property \App\Models\AcademicTitle $academic_title
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class StatusAcademicTitle extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'academic_title_id' => 'int',
		'status_id' => 'int',
		'normal_hours' => 'int',
		'extra_hours' => 'int'
	];

	protected $fillable = [
		'academic_title_id',
		'status_id',
		'normal_hours',
		'extra_hours'
	];

	public function academic_title()
	{
		return $this->belongsTo(\App\Models\AcademicTitle::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
