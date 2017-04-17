<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Apr 2017 13:10:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Reminder
 * 
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property bool $completed
 * @property \Carbon\Carbon $completed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Reminder extends Eloquent
{
	protected $casts = [
		'user_id' => 'int',
		'completed' => 'bool'
	];

	protected $dates = [
		'completed_at'
	];

	protected $fillable = [
		'user_id',
		'code',
		'completed',
		'completed_at'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
