<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 08 Apr 2017 14:07:49 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Lush
 * 
 * @property int $id
 * @property string $lush
 * 
 * @property \Illuminate\Database\Eloquent\Collection $schedules
 *
 * @package App\Models
 */
class Lush extends Eloquent
{
	protected $table = 'lush';
	public $timestamps = false;

	protected $fillable = [
		'lush'
	];

	public function schedules()
	{
		return $this->hasMany(\App\Models\Schedule::class);
	}
}
