<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
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
