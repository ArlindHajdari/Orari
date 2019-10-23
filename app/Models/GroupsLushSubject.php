<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GroupsLushSubject
 *
 * @property int $lush_subjects_id
 * @property int $group_id
 *
 * @property \App\Models\Group $group
 * @property \App\Models\SubjectLush $subject_lush
 *
 * @package App\Models
 */
class GroupsLushSubject extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;
	protected $table = "groups_lushsubjects";
	protected $casts = [
		'lush_subjects_id' => 'int',
		'group_id' => 'int'
	];

	protected $fillable = [
		'lush_subjects_id',
		'group_id'
	];

	public function group()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function subject_lush()
	{
		return $this->belongsTo(\App\Models\SubjectLush::class, 'lush_subjects_id');
	}
}
