<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class SubjectLush
 * 
 * @property int $id
 * @property int $subject_id
 * @property int $lush_id
 * 
 * @property \App\Models\Lush $lush
 * @property \App\Models\Subject $subject
 * @property \App\Models\GroupsLushSubject $groups_lush_subject
 *
 * @package App\Models
 */
class SubjectLush extends Eloquent
{
	protected $table = 'subject_lush';
	public $timestamps = false;

	protected $casts = [
		'subject_id' => 'int',
		'lush_id' => 'int'
	];

	protected $fillable = [
		'subject_id',
		'lush_id'
	];

	public function lush()
	{
		return $this->belongsTo(\App\Models\Lush::class);
	}

	public function subject()
	{
		return $this->belongsTo(\App\Models\Subject::class);
	}

	public function groups_lush_subject()
	{
		return $this->hasOne(\App\Models\GroupsLushSubject::class, 'lush_subjects_id');
	}
}
