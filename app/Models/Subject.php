<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Subject
 * 
 * @property int $id
 * @property string $subject
 * @property int $ects
 * @property int $semester
 * @property int $subjecttype_id
 *
 * @package App\Models
 */
class Subject extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'ects' => 'int',
		'semester' => 'int',
		'subjecttype_id' => 'int'
	];

	protected $fillable = [
		'subject',
		'ects',
		'semester',
		'subjecttype_id'
	];
}