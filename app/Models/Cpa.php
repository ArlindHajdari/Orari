<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cpa
 * 
 * @property int $id
 * @property string $cpa
 *
 * @package App\Models
 */
class Cpa extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'cpa'
	];
}
