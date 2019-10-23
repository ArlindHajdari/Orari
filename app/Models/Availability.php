<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Availability
 * 
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property bool $allowed
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
		'user_id' => 'int',
		'allowed' => 'bool'
	];

	protected $dates = [
		'start',
		'end'
	];

	protected $fillable = [
		'user_id',
		'start',
		'end',
		'allowed'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
