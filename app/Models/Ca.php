<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Ca
 * 
 * @property int $cps_id
 * @property int $user_id
 *
 * @package App\Models
 */
class Ca extends Eloquent
{
	protected $table = 'ca';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cps_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'cps_id',
		'user_id'
	];
}
