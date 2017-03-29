<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cp
 * 
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 *
 * @package App\Models
 */
class Cp extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'subject_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'subject_id'
	];
}
