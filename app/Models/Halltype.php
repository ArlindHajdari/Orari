<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Halltype
 * 
 * @property int $id
 * @property string $hallType
 * 
 * @property \Illuminate\Database\Eloquent\Collection $halls
 *
 * @package App\Models
 */
class Halltype extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'hallType'
	];

	public function halls()
	{
		return $this->hasMany(\App\Models\Hall::class);
	}
}
