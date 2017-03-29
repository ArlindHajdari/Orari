<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
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
}
