<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Faculty
 * 
 * @property int $id
 * @property string $faculty
 *
 * @package App\Models
 */
class Faculty extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'faculty'
	];
}
