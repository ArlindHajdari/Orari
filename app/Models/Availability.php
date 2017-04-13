<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 08 Apr 2017 14:07:49 +0000.
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
 * @property \App\Models\User $user
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

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
