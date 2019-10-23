<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Activation
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
class Activation extends Eloquent
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
