<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Subjecttype
 * 
 * @property int $id
 * @property string $subjecttype
 * 
 * @property \Illuminate\Database\Eloquent\Collection $subjects
 *
 * @package App\Models
 */
class Subjecttype extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'subjecttype'
	];

	public function subjects()
	{
		return $this->hasMany(\App\Models\Subject::class);
	}
}
