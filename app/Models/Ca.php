<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Ca
 * 
 * @property int $cps_id
 * @property int $user_id
 * 
 * @property \App\Models\Cp $cp
 * @property \App\Models\User $user
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

	public function cp()
	{
		return $this->belongsTo(\App\Models\Cp::class, 'cps_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
