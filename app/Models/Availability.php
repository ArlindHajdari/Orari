<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Availability
 * 
 * @property int $id
 * @property \Carbon\Carbon $TimeFrom
 * @property \Carbon\Carbon $TimeTo
 * @property int $user_id
 *
 * @package App\Models
 */
class Availability extends Eloquent
{
	protected $table = 'availability';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'TimeFrom',
		'TimeTo'
	];

	protected $fillable = [
		'TimeFrom',
		'TimeTo',
		'user_id'
	];
}
