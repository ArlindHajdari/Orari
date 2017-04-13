<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 08 Apr 2017 14:07:49 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cpa
 * 
 * @property int $id
 * @property string $cpa
 * 
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Cpa extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'cpa'
	];

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
