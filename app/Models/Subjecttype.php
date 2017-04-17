<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
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
