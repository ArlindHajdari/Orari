<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 30 Mar 2017 17:53:37 +0000.
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
