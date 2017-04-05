<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
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
 * @property \App\Models\Subjecttype $subjecttype
 * @property \Illuminate\Database\Eloquent\Collection $cps
 * @property \Illuminate\Database\Eloquent\Collection $departments
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


	public function subjecttype()
	{
		return $this->belongsTo(\App\Models\Subjecttype::class);
	}

	public function cps()
	{
		return $this->hasMany(\App\Models\Cp::class);
	}

	public function departments()
	{
		return $this->belongsToMany(\App\Models\Department::class, 'department_subjects');
	}

//    protected $table = 'subjects';
//
//    public function subjectDepartment()
//    {
//        return $this->hasMany('subject_id', 'id');
//    }

}
