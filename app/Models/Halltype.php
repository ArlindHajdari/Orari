<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Halltype
 * 
 * @property int $id
 * @property string $hallType
 *
 * @package App\Models
 */
class Halltype extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'hallType'
	];
}
