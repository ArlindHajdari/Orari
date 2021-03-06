<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Throttle
 * 
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Throttle extends Eloquent
{
	protected $table = 'throttle';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'type',
		'ip'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
