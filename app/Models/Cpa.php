<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cpa
 * 
 * @property int $id
 * @property string $cpa
 * 
 * @property \Illuminate\Database\Eloquent\Collection $lushes
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

	public function lushes()
	{
		return $this->belongsToMany(\App\Models\Lush::class, 'lush_cpa');
	}

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
