<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Lush
 * 
 * @property int $id
 * @property string $lush
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
}
